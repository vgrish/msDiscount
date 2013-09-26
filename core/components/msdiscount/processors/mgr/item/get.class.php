<?php
/**
 * Get an Item
 */
class msDiscountItemGetProcessor extends modObjectGetProcessor {
	public $objectType = 'msDiscountItem';
	public $classKey = 'msDiscountItem';
	public $languageTopics = array('msdiscount:default');
}

return 'msDiscountItemGetProcessor';
