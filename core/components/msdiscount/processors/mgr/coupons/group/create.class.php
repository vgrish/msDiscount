<?php

class msdCouponGroupCreateProcessor extends modObjectCreateProcessor {
	public $objectType = 'msdCouponGroup';
	public $classKey = 'msdCouponGroup';
	public $languageTopics = array('msdiscount');
	public $permission = 'msdiscount_save';


	/** {inheritDoc} */
	public function beforeSet() {
		/** @var msDiscount $msDiscount */
		$msDiscount = $this->modx->getService('msDiscount');

		$properties = $this->getProperties();
		foreach ($properties as $k => $v) {
			$properties[$k] = $msDiscount->sanitize($k, $v);
		}
		$this->setProperties($properties);

		$required = array('name', 'discount', 'coupons');
		foreach ($required as $v) {
			$value = trim($this->getProperty($v));
			if (empty($value) || $value == '0%') {
				$this->modx->error->addField($v, $this->modx->lexicon('msd_err_ns'));
			}
		}

		$unique = array('name');
		foreach ($unique as $v) {
			if ($this->modx->getCount($this->classKey, array($v => $this->getProperty($v)))) {
				$this->modx->error->addField($v, $this->modx->lexicon('msd_err_ae'));
			}
		}

		$prefix = $this->getProperty('prefix');
		if (!empty($prefix) && !preg_match('#[A-Z0-9]{5}#i', $prefix)) {
			$this->modx->error->addField('prefix', $this->modx->lexicon('msd_err_prefix'));
		}
		else {
			$this->setProperty('prefix', strtoupper($prefix));
		}

		return parent::beforeSet();
	}

}

return 'msdCouponGroupCreateProcessor';