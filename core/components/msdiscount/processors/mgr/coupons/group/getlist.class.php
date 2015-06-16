<?php

class msdCouponsGroupGetListProcessor extends modObjectGetListProcessor {
	public $objectType = 'msdCouponGroup';
	public $classKey = 'msdCouponGroup';
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
			'icon' => "$icon $icon-edit",
			'title' => $this->modx->lexicon('msd_action_update'),
			'action' => 'updateCoupons',
			'button' => true,
			'menu' => true,
		);

		$array['actions'][] = array(
			'cls' => '',
			'icon' => "$icon $icon-download",
			'title' => $this->modx->lexicon('msd_action_download_coupons'),
			'multiple' => $this->modx->lexicon('msd_action_download_coupons'),
			'action' => 'downloadCoupons',
			'button' => true,
			'menu' => true,
		);

		$array['actions'][] = array(
			'cls' => '',
			'icon' => "$icon $icon-trash-o action-red",
			'title' => $this->modx->lexicon('msd_action_remove'),
			'multiple' => $this->modx->lexicon('msd_action_remove'),
			'action' => 'removeCoupons',
			'button' => true,
			'menu' => true,
		);

		return $array;
	}

}

return 'msdCouponsGroupGetListProcessor';
