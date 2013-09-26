<?php
/**
 * Remove an Item
 */
class msDiscountItemRemoveProcessor extends modObjectRemoveProcessor {
	public $checkRemovePermission = true;
	public $objectType = 'msDiscountItem';
	public $classKey = 'msDiscountItem';
	public $languageTopics = array('msdiscount');

}

return 'msDiscountItemRemoveProcessor';
