<?php
/**
 * Get a list of Items
 */
class msdProductGroupGetListProcessor extends modObjectGetListProcessor {
	public $objectType = 'msdProductGroup';
	public $classKey = 'msdProductGroup';
	public $defaultSortField = 'name';
	public $defaultSortDirection = 'ASC';


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

		return $array;
	}

}

return 'msdProductGroupGetListProcessor';
