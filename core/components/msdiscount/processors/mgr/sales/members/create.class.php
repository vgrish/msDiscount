<?php

class msdSaleMemberCreateProcessor extends modObjectCreateProcessor {
	public $objectType = 'msdSaleMember';
	public $classKey = 'msdSaleMember';
	public $languageTopics = array('msdiscount');
	public $permission = 'msdiscount_save';


	/** {inheritDoc} */
	public function beforeSet() {
		$this->object->fromArray($this->getProperties(), '', true, true);

		return parent::beforeSet();
	}

}

return 'msdSaleMemberCreateProcessor';
