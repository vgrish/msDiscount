<?php
/**
 * Get a list of Items
 */
class msdSaleGroupGetListProcessor extends modObjectGetListProcessor {
	public $objectType = '';
	public $classKey = '';
	public $linkedKey = '';
	public $defaultSortField = 'name';
	public $defaultSortDirection = 'ASC';
	public $renderers = '';


	public function initialize() {
		switch ($this->getProperty('type')) {
			case 'users':
				$this->objectType = $this->classKey = 'modUserGroup';
				$this->linkedKey = 'msdUserGroup';
				break;
			case 'products':
				$this->objectType = $this->classKey = 'modResourceGroup';
				$this->linkedKey = 'msdProductGroup';
				break;
			case 'vendors':
				$this->objectType = $this->classKey = 'msVendor';
				$this->linkedKey = 'msdProductVendorsGroup';
				break;
		}

		if (empty($this->classKey)) {
			return 'Wrong type of group';
		}

		return parent::initialize();
	}

	/**
	 * @param xPDOQuery $c
	 *
	 * @return xPDOQuery
	 */
	public function prepareQueryBeforeCount(xPDOQuery $c) {
		$c->leftJoin($this->linkedKey, $this->linkedKey, $this->classKey.'.id = '.$this->linkedKey.'.id');
		$c->leftJoin('msdSaleMember', 'msdSaleMember',
			array(
				$this->classKey.'.id = msdSaleMember.group_id',
				'msdSaleMember.type' => $this->getProperty('type'),
				'msdSaleMember.sale_id' => $this->getProperty('sale_id'),
			)
		);
    	$c->select($this->modx->getSelectColumns($this->linkedKey, $this->linkedKey));
    	$c->select($this->modx->getSelectColumns($this->classKey, $this->classKey));
    	$c->where(array('msdSaleMember.sale_id' => null));

		$query = $this->getProperty('query');
		if(!empty($query)) {
			$c->andCondition(array(
				'name:LIKE' => '%'.$query.'%',
			));
		}

    	return $c;
	}


	/**
	 * @param xPDOObject $object
	 *
	 * @return array
	 */
	public function prepareRow(xPDOObject $object) {
		$array = $object->toArray();

		return $array;
	}

}

return 'msdSaleGroupGetListProcessor';
