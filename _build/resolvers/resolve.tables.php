<?php
/**
 * @var xPDOObject $object
 * @var array $options
 * @var modX $modx
  */
if ($object->xpdo) {
	$modx =& $object->xpdo;

	switch ($options[xPDOTransport::PACKAGE_ACTION]) {
		case xPDOTransport::ACTION_INSTALL:
			$modelPath = $modx->getOption('msdiscount_core_path',null,$modx->getOption('core_path').'components/msdiscount/').'model/';
			$modx->addPackage('msdiscount', $modelPath);

			$manager = $modx->getManager();
			$tmp = array(
				'msdSale',
				'msdUserGroup',
				'msdProductGroup',
				'msdSaleMember',
			);
			foreach ($tmp as $v) {
				$manager->createObjectContainer($v);
			}
			break;

		case xPDOTransport::ACTION_UPGRADE:
			break;

		case xPDOTransport::ACTION_UNINSTALL:
			break;
	}
}
return true;