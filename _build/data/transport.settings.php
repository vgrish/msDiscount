<?php

$settings = array();

$tmp = array(


	//временные
/*
			'assets_path' => array(
				'xtype' => 'textfield',
				'value' => '{base_path}msdiscount/assets/components/msdiscount/',
				'area' => 'msdiscount_temp',
			),
			'assets_url' => array(
				'xtype' => 'textfield',
				'value' => '/msdiscount/assets/components/msdiscount/',
				'area' => 'msdiscount_temp',
			),
			'core_path' => array(
				'xtype' => 'textfield',
				'value' => '{base_path}msdiscount/core/components/msdiscount/',
				'area' => 'msdiscount_temp',
			),*/


	//временные

	/*
	'some_setting' => array(
		'xtype' => 'combo-boolean',
		'value' => true,
		'area' => 'msdiscount_main',
	),
	*/
);

foreach ($tmp as $k => $v) {
	/* @var modSystemSetting $setting */
	$setting = $modx->newObject('modSystemSetting');
	$setting->fromArray(array_merge(
		array(
			'key' => 'msdiscount_'.$k,
			'namespace' => PKG_NAME_LOWER,
		), $v
	),'',true,true);

	$settings[] = $setting;
}

unset($tmp);
return $settings;


