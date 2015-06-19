<?php

$properties = array();

$tmp = array(
    'tpl' => array(
    	'type' => 'textfield',
		'value' => 'tpl.msProducts.discount.row',
	),
	'limit' => array(
		'type' => 'numberfield',
		'value' => 10,
	),
	'offset' => array(
		'type' => 'numberfield',
		'value' => 0,
	),
	'sortby' => array(
		'type' => 'textfield',
		'value' => 'id',
	),
	'sortdir' => array(
		'type' => 'list',
		'options' => array(
			array('text' => 'ASC','value' => 'ASC'),
			array('text' => 'DESC','value' => 'DESC'),
		),
		'value' => 'ASC',
	),
	'where' => array(
		'type' => 'textfield',
		'value' => '',
	),
	'outputSeparator' => array(
		'type' => 'textfield',
		'value' => "\n"
	),
	'showHidden' => array(
		'type' => 'combo-boolean',
		'value' => true,
	),
	'frontend_css' => array(
		'type' => 'textfield',
		'value' => '[[+assetsUrl]]css/web/main.css',
	),
	'frontend_js' => array(
		'type' => 'textfield',
		'value' => '[[+assetsUrl]]js/web/default.js',
	),
);

foreach ($tmp as $k => $v) {
	$properties[] = array_merge(
		array(
			'name' => $k,
			'desc' => PKG_NAME_LOWER . '_prop_' . $k,
			'lexicon' => PKG_NAME_LOWER . ':properties',
		), $v
	);
}

return $properties;
