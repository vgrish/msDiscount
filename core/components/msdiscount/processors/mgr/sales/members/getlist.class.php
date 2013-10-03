<?php
/**
 * Get a list of Items
 */
class msdSaleMemberGetListProcessor extends modObjectGetListProcessor {
	public $objectType = 'msdSaleMember';
	public $classKey = 'msdSaleMember';
	public $linkedKey1 = '';
	public $linkedKey2 = '';
	public $defaultSortField = 'name';
	public $defaultSortDirection = 'DESC';
	public $renderers = '';


	public function initialize() {
		switch ($this->getProperty('type')) {
			case 'users':
				$this->linkedKey1 = 'msdUserGroup';
				$this->linkedKey2 = 'modUserGroup';
				break;
			case 'products':
				$this->linkedKey1 = 'msdProductGroup';
				$this->linkedKey2 = 'modResourceGroup';
				break;
		}

		return parent::initialize();
	}

	/**
	 * @param xPDOQuery $c
	 *
	 * @return xPDOQuery
	 */
	public function prepareQueryBeforeCount(xPDOQuery $c) {
		$c->where(array(
			'type' => $this->getProperty('type'),
			'sale_id' => $this->getProperty('sale_id'),
		));

		$c->leftJoin($this->linkedKey1, $this->linkedKey1, $this->classKey.'.group_id = '.$this->linkedKey1.'.id');
		$c->leftJoin($this->linkedKey2, $this->linkedKey2, $this->classKey.'.group_id = '.$this->linkedKey2.'.id');

		$c->select($this->modx->getSelectColumns($this->classKey, $this->classKey));
		$c->select($this->modx->getSelectColumns($this->linkedKey1, $this->linkedKey1));
		$c->select($this->modx->getSelectColumns($this->linkedKey2, $this->linkedKey2));

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

return 'msdSaleMemberGetListProcessor';
