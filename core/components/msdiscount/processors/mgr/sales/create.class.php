<?php

class msdSaleCreateProcessor extends modObjectCreateProcessor {
	public $objectType = 'msdSale';
	public $classKey = 'msdSale';
	public $languageTopics = array('msdiscount');
	public $permission = 'msdiscount_save';


	/** {inheritDoc} */
	public function beforeSet() {
		$required = array('discount','name');
		foreach ($required as $v) {
			if ($this->getProperty($v) == '') {
				$this->modx->error->addField($v, $this->modx->lexicon('msd_err_ns'));
			}
		}

		$unique = array('name');
		foreach ($unique as $v) {
			if ($this->modx->getCount($this->classKey, array($v => $this->getProperty($v)))) {
				$this->modx->error->addField($v, $this->modx->lexicon('msd_err_ae'));
			}
		}

		$default = array('begins' => '0000-00-00 00:00:00', 'ends' => '0000-00-00 00:00:00');
		foreach ($default as $k => $v) {
			if (!$this->getProperty($k)) {
				$this->setProperty($k, $v);
			}
		}

		return parent::beforeSet();
	}

}

return 'msdSaleCreateProcessor';
