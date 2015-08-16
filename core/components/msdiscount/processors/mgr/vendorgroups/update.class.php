<?php

class msdProductVendorsGroupUpdateProcessor extends modObjectUpdateProcessor {
	public $objectType = 'msdProductVendorsGroup';
	public $classKey = 'msdProductVendorsGroup';
	public $languageTopics = array('msdiscount');
	public $permission = 'msdiscount_save';


	/** {inheritDoc} */
	public function initialize() {
		$primaryKey = $this->getProperty($this->primaryKeyField,false);
		if (empty($primaryKey)) return $this->modx->lexicon($this->objectType.'_err_ns');

		if (!$this->object = $this->modx->getObject($this->classKey,$primaryKey)) {
			$this->object = $this->modx->newObject($this->classKey);
			$this->object->set($this->primaryKeyField, $primaryKey);
			$this->object->save();
		}

		if ($this->checkSavePermission && $this->object instanceof modAccessibleObject && !$this->object->checkPolicy('save')) {
			return $this->modx->lexicon('access_denied');
		}
		return true;
	}


	/** {inheritDoc} */
	public function beforeSet() {
		$properties = $this->getProperties();
		unset($properties['actions']);
		foreach ($properties as $k => $v) {
			$properties[$k] = $this->modx->msDiscount->sanitize($k, $v);
		}
		$this->setProperties($properties);

		return parent::beforeSet();
	}


	/** {inheritDoc} */
	public function beforeSave() {
		/** @var modProcessorResponse $response */
/*		$response = $this->modx->runProcessor('security/group/update', $this->getProperties());
		if ($response->isError()) {
			return $response->response;
		}*/

		return parent::beforeSave();
	}

}

return 'msdProductVendorsGroupUpdateProcessor';
