<?php

class msdSaleMemberRemoveProcessor extends modObjectRemoveProcessor {
	public $objectType = 'msdSaleMember';
	public $classKey = 'msdSaleMember';
	public $languageTopics = array('msdiscount');
	public $permission = 'msdiscount_save';

	public function initialize() {
		//$primaryKey = $this->getProperty($this->primaryKeyField,false);
		//if (empty($primaryKey)) return $this->modx->lexicon($this->objectType.'_err_ns');
		//$this->object = $this->modx->getObject($this->classKey,$primaryKey);


		$this->object = $this->modx->getObject($this->classKey, array(
			'sale_id' => $this->getProperty('sale_id'),
			'group_id' => $this->getProperty('group_id'),
			'type' => $this->getProperty('type'),
		));

		if (empty($this->object)) return $this->modx->lexicon($this->objectType.'_err_nfs');

		if ($this->permission && $this->object instanceof modAccessibleObject && !$this->modx->hasPermission($this->permission)) {
			return $this->modx->lexicon('access_denied');
		}
		return true;
	}

}

return 'msdSaleMemberRemoveProcessor';
