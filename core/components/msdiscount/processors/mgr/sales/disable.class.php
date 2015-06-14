<?php

require 'update.class.php';

class msdSaleDisableProcessor extends msdSaleUpdateProcessor {

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
			'active' => false,
		));

		return parent::beforeSave();
	}


	/**
	 *
	 */
	public function logManagerAction() {
		$this->modx->logManagerAction($this->objectType . '_disable', $this->classKey, $this->object->get($this->primaryKeyField));
	}

}

return 'msdSaleDisableProcessor';