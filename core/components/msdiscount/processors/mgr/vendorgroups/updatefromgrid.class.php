<?php
require 'update.class.php';

class msdProductVendorsGroupUpdateFromGridProcessor extends msdProductVendorsGroupUpdateProcessor {

	public static function getInstance(modX &$modx,$className,$properties = array()) {
		/** @var modProcessor $processor */
		$processor = new msdProductVendorsGroupUpdateFromGridProcessor($modx,$properties);
		return $processor;
	}


	public function initialize() {
		$data = $this->getProperty('data');
		if (empty($data)) {
			return $this->modx->lexicon('invalid_data');
		}

		$data = $this->modx->fromJSON($data);
		if (empty($data)) {
			return $this->modx->lexicon('invalid_data');
		}

		$this->setProperties($data);
		$this->unsetProperty('data');

		return parent::initialize();
	}


}
return 'msdProductVendorsGroupUpdateFromGridProcessor';