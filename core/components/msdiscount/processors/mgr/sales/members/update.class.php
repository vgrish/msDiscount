<?php

class msdSaleMemberUpdateProcessor extends modObjectUpdateProcessor {
	public $objectType = 'msdSaleMember';
	public $classKey = 'msdSaleMember';
	public $languageTopics = array('msdiscount');
	public $permission = 'msdiscount_save';


	/** {inheritDoc} */
	public function initialize() {
		if (!$this->object = $this->modx->getObject($this->classKey,
			array(
				'sale_id' => $this->getProperty('sale_id'),
				'group_id' => $this->getProperty('group_id'),
				'type' => $this->getProperty('type'),
			)
		)) {
			return $this->modx->lexicon($this->objectType.'_err_nfs');
		};

		if ($this->checkSavePermission && $this->object instanceof modAccessibleObject && !$this->object->checkPolicy('save')) {
			return $this->modx->lexicon('access_denied');
		}
		return true;
	}


	/** {inheritDoc} */
	public function beforeSet() {
		$required = array('relation');
		foreach ($required as $v) {
			if ($this->getProperty($v) == '') {
				$this->modx->error->addField($v, $this->modx->lexicon('msd_err_ns'));
			}
		}

		return parent::beforeSet();
	}

}

return 'msdSaleMemberUpdateProcessor';
