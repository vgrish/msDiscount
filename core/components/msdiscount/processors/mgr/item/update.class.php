<?php
/**
 * Update an Item
 */
class msDiscountItemUpdateProcessor extends modObjectUpdateProcessor {
	public $objectType = 'msDiscountItem';
	public $classKey = 'msDiscountItem';
	public $languageTopics = array('msdiscount');
	public $permission = 'update_document';
}

return 'msDiscountItemUpdateProcessor';
