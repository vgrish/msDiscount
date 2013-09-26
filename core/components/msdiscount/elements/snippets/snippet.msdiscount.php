<?php

/** @var array $scriptProperties */
/** @var msDiscount $msDiscount */
$msDiscount = $modx->getService('msdiscount', 'msDiscount', MODX_CORE_PATH . 'components/msdiscount/model/msdiscount/', $scriptProperties);

echo '<pre>';

if ($msdSale = $modx->getObject('msdSale', 1)) {
	print_r($msdSale->toArray());
}

if ($tmp = $msdSale->getMany('Members')) {
	foreach ($tmp as $v) {
		print_r($v->toArray());
	}
}

if ($tmp = $modx->getObject('msdUserGroup', 1)) {
	print_r($tmp->toArray());
	$tmp = $tmp->getMany('Members');
	foreach ($tmp as $v) {
		print_r($v->toArray());
	}
}

if ($tmp = $modx->getObject('msdProductGroup', 1)) {
	print_r($tmp->toArray());
	$tmp = $tmp->getMany('Members');
	foreach ($tmp as $v) {
		print_r($v->toArray());
	}
}

echo '</pre>';