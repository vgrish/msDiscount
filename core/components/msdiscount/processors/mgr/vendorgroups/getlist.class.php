<?php

/**
 * Get a list of Items
 */
class msdProductVendorsGroupGetListProcessor extends modObjectGetListProcessor
{
	public $objectType = 'msdProductVendorsGroup';
	public $classKey = 'msdProductVendorsGroup';
	public $defaultSortField = 'name';
	public $defaultSortDirection = 'ASC';
	public $total = 0;
	protected $_modx23;


	/**
	 * @return bool
	 */
	public function initialize()
	{
		$parent = parent::initialize();
		/** @var msDiscount $msDiscount */
		$msDiscount = $this->modx->getService('msDiscount');
		$this->_modx23 = $msDiscount->systemVersion();

		return $parent;
	}

	/**
	 * {@inheritDoc}
	 * @return mixed
	 */
	public function process()
	{
		$beforeQuery = $this->beforeQuery();
		if ($beforeQuery !== true) {
			return $this->failure($beforeQuery);
		}
		$data = $this->getData();
		$list = $this->iterate($data);
		return $this->outputArray($list, $this->total);
	}

	/**
	 * @param xPDOQuery $c
	 *
	 * @return xPDOQuery
	 */
	public function prepareQueryBeforeCount(xPDOQuery $c)
	{
		$c->rightJoin('msVendor', 'msVendor', 'msVendor.id = msdProductVendorsGroup.id');
		$c->select($this->modx->getSelectColumns($this->classKey, $this->classKey));
		$c->select($this->modx->getSelectColumns('msVendor', 'msVendor'));
		return $c;
	}

	/**
	 * @param xPDOObject $object
	 *
	 * @return array
	 */
	public function prepareRow(xPDOObject $object)
	{
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

	/** {@inheritDoc} */
	public function iterate(array $data)
	{
		$list = array();
		$list = $this->beforeIteration($list);
		$this->currentIndex = 0;
		/** @var xPDOObject|modAccessibleObject $object */
		foreach ($data['results'] as $array) {
			$list[] = $this->prepareRow($array);
			$this->currentIndex++;
		}
		$list = $this->afterIteration($list);

		return $list;
	}

	public function afterIteration(array $list)
	{
		$data = array();
		$limit = intval($this->getProperty('limit'));
		$start = intval($this->getProperty('start'));
		$this->total = count($list);
		foreach ($list as $i => $v) {
			if ($i < $start) {
				continue;
			}
			if (count($data) >= $limit) {
				break;
			}
			$data[] = $v;
		}

		return $data;
	}

	public function getData()
	{
		$data = array();
		$c = $this->modx->newQuery($this->classKey);
		$c = $this->prepareQueryBeforeCount($c);
		$data['total'] = $this->modx->getCount($this->classKey, $c);
		$c = $this->prepareQueryAfterCount($c);
		$sortClassKey = $this->getSortClassKey();
		$sortKey = $this->modx->getSelectColumns($sortClassKey, $this->getProperty('sortAlias', $sortClassKey), '', array($this->getProperty('sort')));
		if (empty($sortKey)) $sortKey = $this->getProperty('sort');
		$c->sortby($sortKey, $this->getProperty('dir'));
		$data['results'] = $this->modx->getCollection($this->classKey, $c);

		return $data;
	}
}

return 'msdProductVendorsGroupGetListProcessor';
