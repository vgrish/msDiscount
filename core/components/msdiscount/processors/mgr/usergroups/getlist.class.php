<?php
/**
 * Get a list of Items
 */
class msdUserGroupGetListProcessor extends modObjectGetListProcessor {
	public $objectType = 'msdUserGroup';
	public $classKey = 'msdUserGroup';
	public $defaultSortField = 'name';
	public $defaultSortDirection = 'ASC';


	/**
	 * @param xPDOQuery $c
	 *
	 * @return xPDOQuery
	 */
	public function prepareQueryBeforeCount(xPDOQuery $c) {
		$c->rightJoin('modUserGroup', 'modUserGroup', 'modUserGroup.id = msdUserGroup.id');
		$c->select($this->modx->getSelectColumns($this->classKey, $this->classKey));
		$c->select($this->modx->getSelectColumns('modUserGroup', 'modUserGroup'));
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

return 'msdUserGroupGetListProcessor';
