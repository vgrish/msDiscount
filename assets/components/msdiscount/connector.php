<?php

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$corePath = $modx->getOption('msdiscount_core_path', null, $modx->getOption('core_path') . 'components/msdiscount/');
require_once $corePath . 'model/msdiscount/msdiscount.class.php';
$modx->msDiscount = new msDiscount($modx);

$modx->lexicon->load('msdiscount:default');

/* handle request */
$path = $modx->getOption('processorsPath', $modx->msDiscount->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
	'processors_path' => $path,
	'location' => '',
));
