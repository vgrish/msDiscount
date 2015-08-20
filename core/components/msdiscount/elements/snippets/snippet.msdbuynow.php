<?php
/** @var array $scriptProperties */
/** @var pdoTools $pdoTools */
$pdoTools = $modx->getService('pdoTools');
/** @var msDiscount $msDiscount */
$msDiscount = $modx->getService('msDiscount');
if (!empty($frontend_css)) {
	$frontend_css = str_replace('[[+assetsUrl]]', $msDiscount->config['assetsUrl'], $frontend_css);
	$modx->regClientCSS($frontend_css);
}
if (!empty($frontend_js)) {
	$frontend_js = str_replace('[[+assetsUrl]]', $msDiscount->config['assetsUrl'], $frontend_js);
	$modx->regClientScript($frontend_js);
}

$date = date('Y-m-d H:i:s');
$pdoTools->setStore('msd_date', $date);
$usergroups = array_keys($msDiscount->getUserGroups($modx->user->id));
$sales = $msDiscount->getSales($date, true);
if (!empty($sale)) {
	$pdoTools->setStore('msd_sale', $sale);
	$tmp = array_map('trim', explode(',', $sale));
	foreach ($sales as $id => $sale) {
		if (!in_array($id, $tmp)) {
			unset($sales[$id]);
		}
	}
}
if (empty($sales)) {
	return !empty($showLog) && $modx->user->hasSessionContext('mgr')
		? $modx->lexicon('msd_err_no_sales')
		: '';
}
$all = false;
$parents_in = $parents_out = $vendors_in = $vendors_out = array();
foreach ($sales as $idx => $sale) {
	// Check user groups
	if (!empty($sale['users'])) {
		$required = array();
		foreach ($sale['users'] as $gid => $type) {
			if ($type == 'out' && in_array($gid, $usergroups)) {
				unset($sales[$idx]);
				continue(2);
			}
			if ($type == 'in') {
				$required[] = $gid;
			}
		}
		if (!empty($required) && !array_intersect($required, $usergroups)) {
			unset($sales[$idx]);
			continue;
		}
	}
	// Check product groups
	if (!empty($sale['products'])) {
		foreach ($sale['products'] as $gid => $type) {
			$c = $modx->newQuery('modResourceGroupResource', array('document_group' => $gid));
			$c->select('document');
			$tstart = microtime(true);
			if ($c->prepare() && $c->stmt->execute()) {
				$modx->queryTime += microtime(true) - $tstart;
				$modx->executedQueries++;
				if ($ids = $c->stmt->fetchAll(PDO::FETCH_COLUMN)) {
					if ($type == 'in') {
						$parents_in = array_merge($parents_in, $ids);
					} else {
						$parents_out = array_merge($parents_out, $ids);
					}
				}
			}
		}
	}
	// Check vendors groups
	if (!empty($sale['vendors'])) {
		foreach ($sale['vendors'] as $gid => $type) {
			if ($type == 'in') {
				$vendors_in[] = $gid;
			} else {
				$vendors_out[] = $gid;
			}
		}
	} // All products
	if (empty($sale['users']) && empty($sale['products']) && empty($sale['vendors'])) {
		$all = true;
		break;
	}
}
if (!$parents_in && !$parents_out && !$vendors_in && !$vendors_out && !$all) {
	return !empty($showLog) && $modx->user->hasSessionContext('mgr')
		? $modx->lexicon('msd_err_no_sales')
		: '';
}

if (empty($scriptProperties['prepareSnippet'])) {
	$scriptProperties['prepareSnippet'] = 'msdGetDiscount';
}

$scriptProperties['parents'] = 0;
if (!empty($scriptProperties['where'])) {
	$where = !is_array($scriptProperties['where'])
		? $modx->fromJSON($scriptProperties['where'])
		: $scriptProperties['where'];
} else {
	$where = array();
}
if (!empty($vendors_in)) {
	$c = $modx->newQuery('msProductData');
	$c->where(array(
		'vendor:IN' => $vendors_in,
	));
	$c->select('id');
	$tstart = microtime(true);
	if ($c->prepare() && $c->stmt->execute()) {
		$modx->queryTime += microtime(true) - $tstart;
		$modx->executedQueries++;
		if ($ids = $c->stmt->fetchAll(PDO::FETCH_COLUMN)) {
			$parents_in = array_merge($parents_in, $ids);
		}
	}
}
if (!empty($vendors_out)) {
	$c = $modx->newQuery('msProductData');
	$c->where(array(
		'vendor:IN' => $vendors_out,
	));
	$c->select('id');
	$tstart = microtime(true);
	if ($c->prepare() && $c->stmt->execute()) {
		$modx->queryTime += microtime(true) - $tstart;
		$modx->executedQueries++;
		if ($ids = $c->stmt->fetchAll(PDO::FETCH_COLUMN)) {
			$parents_out = array_merge($parents_out, $ids);
		}
	}
}
if (!$all) {
	$depth = (isset($config['depth']) && $config['depth'] !== '')
		? (integer)$config['depth']
		: 10;
	if (!empty($depth) && $depth > 0) {
		$pids = array();
		$q = $modx->newQuery('modResource', array('id:IN' => array_merge($parents_in, $parents_out)));
		$q->select('id,context_key');
		$tstart = microtime(true);
		if ($q->prepare() && $q->stmt->execute()) {
			$modx->queryTime += microtime(true) - $tstart;
			$modx->executedQueries++;
			while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
				$pids[$row['id']] = $row['context_key'];
			}
		}
		foreach ($pids as $k => $v) {
			if (!is_numeric($k)) {
				continue;
			} elseif (in_array($k, $parents_in)) {
				$parents_in = array_merge($parents_in, $modx->getChildIds($k, $depth, array('context' => $v)));
			} else {
				$parents_out = array_merge($parents_out, $modx->getChildIds($k, $depth, array('context' => $v)));
			}
		}
		if (empty($parents_in)) {
			$parents_in = $modx->getChildIds(0, $depth, array('context' => $this->config['context']));
		}
		// Support of miniShop2 categories
		$members = array();
		if (!empty($parents_in) || !empty($parents_out)) {
			$q = $modx->newQuery('msCategoryMember');
			if (!empty($parents_in)) {
				$q->where(array('category_id:IN' => $parents_in));
			}
			if (!empty($parents_out)) {
				$q->where(array('category_id:NOT IN' => $parents_out));
			}
			$q->select('product_id');
			$tstart = microtime(true);
			if ($q->prepare() && $q->stmt->execute()) {
				$modx->queryTime += microtime(true) - $tstart;
				$modx->executedQueries++;
				$members = $q->stmt->fetchAll(PDO::FETCH_COLUMN);
			}
		}
		// Add parent to conditions
		if (!empty($parents_in) && !empty($members)) {
			$members = array_merge($members, $parents_in);
			$where[] = array(
				'parent:IN' => $parents_in,
				'OR:id:IN' => $members,
			);
		} elseif (!empty($parents_in)) {
			$where[] = array(
				'parent:IN' => $parents_in,
				'OR:id:IN' => $parents_in,
			);
		}
		if (!empty($parents_out)) {
			$where[] = array(
				'parent:NOT IN' => $parents_out,
				'AND:id:NOT IN' => $parents_out,
			);
		}
	}
}
//$scriptProperties['showLog'] = 1;
$scriptProperties['where'] = $modx->toJSON($where);

return $modx->runSnippet('msProducts', $scriptProperties);