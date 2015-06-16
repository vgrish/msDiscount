<?php

require 'update.class.php';

class msdSaleMemberSwitchProcessor extends msdSaleMemberUpdateProcessor {


	/**
	 * @return bool
	 */
	public function beforeSet() {
		$this->properties = array();

		$relation = $this->object->get('relation') == 'in'
			? 'out'
			: 'in';
		$this->setProperty('relation', $relation);

		return parent::beforeSet();
	}

}

return 'msdSaleMemberSwitchProcessor';
