<?php

class msdCouponsGetListProcessor extends modObjectGetListProcessor {
	public $objectType = 'msdCoupon';
	public $classKey = 'msdCoupon';
	public $defaultSortField = 'id';
	public $defaultSortDirection = 'DESC';
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
		$c->where(array(
			'group_id' => $this->getProperty('group_id')
		));
		$c->leftJoin('msOrder', 'Order');
		$c->select($this->modx->getSelectColumns($this->classKey, $this->classKey));
		$c->select($this->modx->getSelectColumns('msOrder', 'Order', '', array('num')));

		return $c;
	}


	/**
	 * @param xPDOObject $object
	 *
	 * @return array
	 */
	public function prepareRow(xPDOObject $object) {
		$array = $object->toArray('', true);

		$icon = $this->_modx23 ? 'icon' : 'fa';
		$array['actions'] = array();

		$array['actions'][] = array(
			'cls' => '',
			'icon' => "$icon $icon-trash-o action-red",
			'title' => $this->modx->lexicon('msd_action_remove'),
			'multiple' => $this->modx->lexicon('msd_action_remove'),
			'action' => 'removeCoupon',
			'button' => true,
			'menu' => true,
		);

		return $array;
	}

}

return 'msdCouponsGetListProcessor';
