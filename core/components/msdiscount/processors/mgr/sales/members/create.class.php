<?php

class msdSaleMemberCreateProcessor extends modObjectCreateProcessor {
	public $objectType = 'msdSaleMember';
	public $classKey = 'msdSaleMember';
	public $languageTopics = array('msdiscount');
	public $permission = 'msdiscount_save';


	/** {inheritDoc} */
	public function beforeSet() {
		$required = array('sale_id','group_id','type');
		foreach ($required as $v) {
			if ($this->getProperty($v) == '') {
				$this->modx->error->addField($v, $this->modx->lexicon('msd_err_ns'));
			}
		}

		$this->object->fromArray($this->getProperties(), '', true, true);
		return parent::beforeSet();
	}

}

return 'msdSaleMemberCreateProcessor';
