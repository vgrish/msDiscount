<?php

$settings = array();

$tmp = array(
	'some_setting' => array(
		'xtype' => 'combo-boolean',
		'value' => true,
		'area' => 'msdiscount_main',
	),
);

foreach ($tmp as $k => $v) {
	/* @var modSystemSetting $setting */
	$setting = $modx->newObject('modSystemSetting');
	$setting->fromArray(array_merge(
		array(
			'key' => 'msdiscount_'.$k,
			'namespace' => PKG_NAME_LOWER.':default',
		), $v
	),'',true,true);

	$settings[] = $setting;
}

unset($tmp);
return $settings;
