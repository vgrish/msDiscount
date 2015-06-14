<?php

/**
 * Get a list of Items
 */
class msdProductGroupGetListProcessor extends modObjectGetListProcessor {
	public $objectType = 'msdProductGroup';
	public $classKey = 'msdProductGroup';
	public $defaultSortField = 'name';
	public $defaultSortDirection = 'ASC';
	protected $_modx23;


	/**
	 * @return bool
	 */
	public function initialize() {
		$parent = parent::initialize();

		/** @var msDiscount $msDiscount */
		$msDiscount = $this->modx->getService('msDiscount');
		$this->_modx23 = $msDiscount->systemVersion();

		return $parent;
	}


	/**
	 * @param xPDOQuery $c
	 *
	 * @return xPDOQuery
	 */
	public function prepareQueryBeforeCount(xPDOQuery $c) {
		$c->rightJoin('modResourceGroup', 'modResourceGroup', 'modResourceGroup.id = msdProductGroup.id');
		$c->select($this->modx->getSelectColumns($this->classKey, $this->classKey));
		$c->select($this->modx->getSelectColumns('modResourceGroup', 'modResourceGroup'));
		return $c;
	}


	/**
	 * @param xPDOObject $object
	 *
	 * @return array
	 */
	public function prepareRow(xPDOObject $object) {
		$array = $object->toArray();

		$icon = $this->_modx23 ? 'icon' : 'fa';
		$array['actions'] = array();

		$array['actions'][] = array(
			'cls' => '',
			'icon' => "$icon $icon-share",
			'title' => $this->modx->lexicon('msd_action_update'),
			'multiple' => $this->modx->lexicon('msd_action_update'),
			'action' => 'updateGroup',
			'button' => true,
			'menu' => true,
		);

		return $array;
	}

}

return 'msdProductGroupGetListProcessor';
