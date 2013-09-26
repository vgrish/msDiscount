<?php
/**
 * The home manager controller for msDiscount.
 *
 */
class msDiscountHomeManagerController extends msDiscountMainController {
	/* @var msDiscount $msDiscount */
	public $msDiscount;


	/**
	 * @param array $scriptProperties
	 */
	public function process(array $scriptProperties = array()) {
	}


	/**
	 * @return null|string
	 */
	public function getPageTitle() {
		return $this->modx->lexicon('msdiscount');
	}


	/**
	 * @return void
	 */
	public function loadCustomCssJs() {
		$this->modx->regClientStartupScript($this->msDiscount->config['jsUrl'] . 'mgr/widgets/items.grid.js');
		$this->modx->regClientStartupScript($this->msDiscount->config['jsUrl'] . 'mgr/widgets/home.panel.js');
		$this->modx->regClientStartupScript($this->msDiscount->config['jsUrl'] . 'mgr/sections/home.js');
	}


	/**
	 * @return string
	 */
	public function getTemplateFile() {
		return $this->msDiscount->config['templatesPath'] . 'home.tpl';
	}
}
