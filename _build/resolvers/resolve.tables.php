<?php

if ($object->xpdo) {
	/* @var modX $modx */
	$modx =& $object->xpdo;

	switch ($options[xPDOTransport::PACKAGE_ACTION]) {
		case xPDOTransport::ACTION_INSTALL:
			$modelPath = $modx->getOption('msdiscount_core_path',null,$modx->getOption('core_path').'components/msdiscount/').'model/';
			$modx->addPackage('msdiscount', $modelPath);

			$manager = $modx->getManager();
			$manager->createObjectContainer('msDiscountItem');
			break;

		case xPDOTransport::ACTION_UPGRADE:
			break;

		case xPDOTransport::ACTION_UNINSTALL:
			break;
	}
}
return true;
