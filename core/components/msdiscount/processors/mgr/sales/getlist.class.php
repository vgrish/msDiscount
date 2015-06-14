<?php
/**
 * Get a list of Items
 */
class msdSalesGetListProcessor extends modObjectGetListProcessor {
	public $objectType = 'msdSale';
	public $classKey = 'msdSale';
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
			'action' => 'updateSale',
			'button' => true,
			'menu' => true,
		);

		if ($array['active']) {
			$array['actions'][] = array(
				'cls' => '',
				'icon' => "$icon $icon-power-off action-yellow",
				'title' => $this->modx->lexicon('msd_action_disable'),
				'multiple' => $this->modx->lexicon('msd_action_disable'),
				'action' => 'disableSale',
				'button' => true,
				'menu' => true,
			);
		}
		else {
			$array['actions'][] = array(
				'cls' => '',
				'icon' => "$icon $icon-power-off action-green",
				'title' => $this->modx->lexicon('msd_action_enable'),
				'multiple' => $this->modx->lexicon('msd_action_enable'),
				'action' => 'enableSale',
				'button' => true,
				'menu' => true,
			);
		}

		$array['actions'][] = array(
			'cls' => '',
			'icon' => "$icon $icon-trash-o action-red",
			'title' => $this->modx->lexicon('msd_action_remove'),
			'multiple' => $this->modx->lexicon('msd_action_remove'),
			'action' => 'removeSale',
			'button' => true,
			'menu' => true,
		);

		return $array;
	}

}

return 'msdSalesGetListProcessor';
