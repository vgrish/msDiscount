<?php
if (empty($tpl)) $tpl = "tpl.msProducts.row";
if (empty($limit)) $limit = 10;
if (empty($offset)) $offset = 0;
if (empty($sortby)) $sortby = "id";
if (empty($sortdir)) $sortdir = "ASC";
if (empty($showHidden)) $showHidden = true;
if (empty($outputSeparator)) $outputSeparator = "\n";

$miniShop2 = $modx->getService('miniShop2');
$msDiscount = $modx->getService('msDiscount');
$modx->regClientCSS($modx->getOption('assets_url').'components/msdiscount/css/web/main.css');
/*$modx->regClientScript(preg_replace(array('/^\n/', '/\t{7}/'), '', '
<script type="text/javascript">
	if(typeof jQuery == "undefined") {
		document.write("<script src=\"'.$modx->getOption('assets_url').'components/msdiscount/js/web/lib/jquery.min.js\" type=\"text/javascript\"><\/script>");
	}
</script>
	'), true);*/
$modx->regClientScript($modx->getOption('assets_url').'components/msdiscount/js/web/default.js');

$q = $modx->newQuery('modUserGroupMember', array('member' => $modx->user->id));
$q->select('user_group');
$groups = array();
if ($q->prepare() && $q->stmt->execute()) {
    while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
		$groups[] = $row['user_group'];
	}
}

$q = $modx->newQuery('msdSale');
if (!empty($sale)) {
    $q->where(array('`msdSale`.`id`:IN' => explode(',', $sale)));
}
$q->where(array(
    'now() between `msdSale`.`begins` and `msdSale`.`ends`'
));
if (!empty($where)) {
    $tmp = array();
    foreach ($modx->fromJSON($where) as $key => $val) {
        $tmp['`modResource`.`'.$key.'`'] = $val;
    }
    $q->where($tmp);
}
$q->select(array('`modResourceGroupResource`.`document`','`msdSaleUserGroups`.`group_id`','`msdSale`.`active`', '`modResourceGroup`.`id` AS `group_id`'));
$q->select(array('`msdSale`.`id` AS `sale_id`, `msdSale`.`discount` AS `sale_discount`, `msdSale`.`name` AS `sale_name`,
    `msdSale`.`description` AS `sale_description`, `msdSale`.`begins` AS `sale_begins`, `msdSale`.`ends` AS `sale_ends`,
    `msdSale`.`active` AS `sale_active`, `msdSale`.`resource` AS `sale_resource`, `msdSale`.`image` AS `sale_image`'));
$q->select(array($modx->getSelectColumns('msProductData','Data')));
$q->select(array($modx->getSelectColumns('msVendor','Vendor')));
$q->select(array($modx->getSelectColumns('modResource','modResource')));
$q->rightJoin('msdSaleMember','msdSaleResourceGroups', array('`msdSaleResourceGroups`.`sale_id` = `msdSale`.`id` AND `msdSaleResourceGroups`.`type` = "products"'));
$q->leftJoin('msdSaleMember','msdSaleUserGroups', array('`msdSaleUserGroups`.`sale_id` = `msdSale`.`id` AND `msdSaleUserGroups`.`type` = "users"'));
$q->rightJoin('modResourceGroup','modResourceGroup', array('`modResourceGroup`.`id` = `msdSaleResourceGroups`.`group_id`'));
$q->rightJoin('modResourceGroupResource','modResourceGroupResource', array('`modResourceGroup`.`id` = `modResourceGroupResource`.`document_group`'));
$q->leftJoin('modResource','modResource', array('`modResource`.`id` = `modResourceGroupResource`.`document`'));
$q->leftJoin('msProductData','Data', array('`modResource`.`id` = `Data`.`id`'));
$q->leftJoin('msVendor','Vendor', array('`Vendor`.`id` = `Data`.`vendor`'));
$q->sortby("`modResource`.`$sortby`", $sortdir);
$q->limit($limit,$offset);
$q->sortby('MAX(`msdSale`.`discount`)', 'DESC');
if ($groups) {
    $q->groupby('`modResourceGroupResource`.`document` HAVING (`msdSaleUserGroups`.`group_id` IS NULL OR `msdSaleUserGroups`.`group_id` IN ('.implode(',', $groups).')) AND `msdSale`.`active` = 1');
} else {
    $q->groupby('`modResourceGroupResource`.`document` HAVING `msdSaleUserGroups`.`group_id` IS NULL AND `msdSale`.`active` = 1');
}

$output = array();
if ($q->prepare() && $q->stmt->execute()) {
    if (!$modx->getObject('modChunk', array('name' => $tpl))) {
        $outType = 'array';
    }
	while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
        if ($row['old_price'] == 0) {
	        $row['old_price'] = $row['price'];
	    }
	    if (strpos($row['sale_discount'], '%') !== false) {
	        $row['price'] = $row['price'] - $row['price'] * $row['sale_discount'] / 100;
	    } else {
	        $row['price'] = $row['price'] - $row['sale_discount'];
	    }
        $row['remains'] = strtotime($row['sale_ends']) - time();
		if ($outType == 'array') {
    	   $output[] = $row;
		} else {
            $output[] = $modx->getChunk($tpl, $row);
		}
	}
}

if ($outType == 'array') {
    print "<pre>";
    print_r($output);
    print "</pre>";
} else {
    return implode($outputSeparator, $output);
}