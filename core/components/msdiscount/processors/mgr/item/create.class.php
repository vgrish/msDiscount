<?php
/**
 * Create an Item
 */
class msDiscountItemCreateProcessor extends modObjectCreateProcessor {
	public $objectType = 'msDiscountItem';
	public $classKey = 'msDiscountItem';
	public $languageTopics = array('msdiscount');
	public $permission = 'new_document';


	/**
	 * @return bool
	 */
	public function beforeSet() {
		$alreadyExists = $this->modx->getObject('msDiscountItem', array(
			'name' => $this->getProperty('name'),
		));
		if ($alreadyExists) {
			$this->modx->error->addField('name', $this->modx->lexicon('msdiscount_item_err_ae'));
		}

		return !$this->hasErrors();
	}

}

return 'msDiscountItemCreateProcessor';
