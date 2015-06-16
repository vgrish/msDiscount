<?php

class msdCouponGroupRemoveProcessor extends modObjectRemoveProcessor {
	public $objectType = 'msdCouponGroup';
	public $classKey = 'msdCouponGroup';
	public $languageTopics = array('msdiscount');
	public $permission = 'msdiscount_save';


	/**
	 * @return bool|null|string
	 */
	public function initialize() {
		$primaryKey = $this->getProperty($this->primaryKeyField, false);
		if (empty($primaryKey)) return $this->modx->lexicon($this->objectType . '_err_ns');
		$this->object = $this->modx->getObject($this->classKey, $primaryKey);
		if (empty($this->object)) return $this->modx->lexicon($this->objectType . '_err_nfs', array($this->primaryKeyField => $primaryKey));

		if ($this->permission && $this->object instanceof modAccessibleObject && !$this->modx->hasPermission($this->permission)) {
			return $this->modx->lexicon('access_denied');
		}
		return true;
	}

}

return 'msdCouponGroupRemoveProcessor';
