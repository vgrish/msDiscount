<?php

class msdCouponGroupUpdateProcessor extends modObjectUpdateProcessor {
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

		$required = array('name', 'discount');
		foreach ($required as $v) {
			$value = trim($this->getProperty($v));
			if (empty($value) || $value == '0%') {
				$this->modx->error->addField($v, $this->modx->lexicon('msd_err_ns'));
			}
		}

		$unique = array('name');
		foreach ($unique as $v) {
			if ($this->modx->getCount($this->classKey, array($v => $this->getProperty($v), 'id:!=' => $this->object->id))) {
				$this->modx->error->addField($v, $this->modx->lexicon('msd_err_ae'));
			}
		}

		$coupons = $this->getProperty('coupons');
		if ($coupons < $this->object->get('coupons')) {
			$this->unsetProperty('coupons');
		}
		$this->unsetProperty('prefix');

		return parent::beforeSet();
	}

}

return 'msdCouponGroupUpdateProcessor';