<?php

require 'update.class.php';

class msdSaleEnableProcessor extends msdSaleUpdateProcessor {

	/**
	 *
	 */
	public function beforeSet() {
		$this->properties = array();

		return true;
	}


	/**
	 * @return bool|null|string
	 */
	public function beforeSave() {
		$this->object->fromArray(array(
			'active' => true,
		));

		return parent::beforeSave();
	}


	/**
	 *
	 */
	public function logManagerAction() {
		$this->modx->logManagerAction($this->objectType . '_enable', $this->classKey, $this->object->get($this->primaryKeyField));
	}

}

return 'msdSaleEnableProcessor';