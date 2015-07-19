<?php
/** @var msDiscount $msDiscount */
/** @var pdoTools $pdoTools */
$pdoTools = $modx->getService('pdoTools');
$msDiscount = $modx->getService('msDiscount');
$date = $pdoTools->getStore('msd_date');
$sales = !empty($date)
	? $msDiscount->getSales($date, true)
	: $msDiscount->getSales();
if (empty($sale)) {
	$sale = $pdoTools->getStore('msd_sale');
}
if (!empty($sale)) {
	$tmp = array_map('trim', explode(',', $sale));
	foreach ($sales as $id => $sale) {
		if (!in_array($id, $tmp)) {
			unset($sales[$id]);
		}
	}
}
if (isset($row) && is_array($row)) {
	$mode = 'prepare';
	if (!empty($row['id'])) {
		$id = $row['id'];
	}
}
else {
	$mode = 'standalone';
	if (!empty($frontend_css)) {
		$frontend_css = str_replace('[[+assetsUrl]]', $msDiscount->config['assetsUrl'], $frontend_css);
		$modx->regClientCSS($frontend_css);
	}
	if (!empty($frontend_js)) {
		$frontend_js = str_replace('[[+assetsUrl]]', $msDiscount->config['assetsUrl'], $frontend_js);
		$modx->regClientScript($frontend_js);
	}
	if (empty($id)) {
		$id = $modx->resource->id;
	}
}

$remains = $discount = 0;
if (!empty($id) && !empty($sales)) {
	$groups = array();
	$ids = $modx->getParentIds($id);
	$ids[] = $id;

	$q = $modx->newQuery('msCategoryMember', array('product_id' => $id));
	$q->select('category_id');
	$tstart = microtime(true);
	if ($q->prepare() && $q->stmt->execute()) {
		$modx->queryTime += microtime(true) - $tstart;
		$modx->executedQueries++;
		if ($tmp = $q->stmt->fetchAll(PDO::FETCH_COLUMN)) {
			$ids = array_merge($ids, $tmp);
		}
	}
	$ids = array_unique($ids);
	$where = count($ids) > 1
		? array('document:IN' => $ids)
		: array('document' => $ids[0]);

	$q = $modx->newQuery('modResourceGroupResource', $where);
	$q->select('document_group');
	$tstart = microtime(true);

	if ($q->prepare() && $q->stmt->execute()) {
		$modx->queryTime += microtime(true) - $tstart;
		$modx->executedQueries++;
		while ($group = $q->stmt->fetchColumn()) {
			foreach ($sales as $sale) {
				if (empty($sale['products']) || isset($sale['products'][$group])) {
					$tmp_remains = ($sale['ends'] != '0000-00-00 00:00:00')
						? strtotime($sale['ends']) - time()
						: 0;
					if (strpos($sale['discount'], '%') !== false && (empty($discount) || strpos($discount, '%') !== false)) {
						if ($sale['discount'] > $discount) {
							$discount = $sale['discount'];
							$remains = $tmp_remains;
						}
					}
					elseif (is_numeric($sale['discount']) && is_numeric($discount)) {
						if ($sale['discount'] > $discount) {
							$discount = $sale['discount'];
							$remains = $tmp_remains;
						}
					}
					else {
						$discount = $sale['discount'];
						$remains = $tmp_remains;
					}
				}
			}
		}
	}
}

$arr = array(
	'sale_discount' => $discount,
	'remains' => $remains,
);
if ($mode == 'standalone') {
	$pdoTools->config['nestedChunkPrefix'] = 'minishop2_';

	return !empty($tpl)
		? $pdoTools->getChunk($tpl, $arr)
		: print_r($arr, true);
}
else {
	$row = array_merge($row, $arr);

	return $modx->toJSON($row);
}