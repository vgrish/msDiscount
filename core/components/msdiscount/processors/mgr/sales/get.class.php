<?php

class msdSaleGetProcessor extends modObjectGetProcessor {
	public $objectType = 'msdSale';
	public $classKey = 'msdSale';
	public $languageTopics = array('msdiscount:default');
	public $permission = 'msdiscount_view';


	/** {inheritDoc} */
	public function initialize() {
		$primaryKey = $this->getProperty($this->primaryKeyField,false);
		if (empty($primaryKey)) return $this->modx->lexicon($this->objectType.'_err_ns');
		$this->object = $this->modx->getObject($this->classKey,$primaryKey);
		if (empty($this->object)) return $this->modx->lexicon($this->objectType.'_err_nfs',array($this->primaryKeyField => $primaryKey));

		if ($this->permission && $this->object instanceof modAccessibleObject && !$this->modx->hasPermission($this->permission)) {
			return $this->modx->lexicon('access_denied');
		}

		return true;
	}


	/** {inheritDoc} */
	public function cleanup() {
		$array = $this->object->toArray();

		foreach (array('begins','ends') as $v) {
			if (isset($array[$v]) && $v == '0000-00-00 00:00:00') {
				$array[$v] = '';
			}
		}

		return $this->success('', $array);
	}

}

return 'msdSaleGetProcessor';
