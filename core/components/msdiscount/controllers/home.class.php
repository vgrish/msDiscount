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
		$this->addJavascript($this->msDiscount->config['jsUrl'] . 'mgr/widgets/usergroups.grid.js');
		$this->addJavascript($this->msDiscount->config['jsUrl'] . 'mgr/widgets/productgroups.grid.js');
		$this->addJavascript($this->msDiscount->config['jsUrl'] . 'mgr/widgets/sales.grid.js');
		$this->addJavascript($this->msDiscount->config['jsUrl'] . 'mgr/widgets/check.form.js');
		$this->addJavascript($this->msDiscount->config['jsUrl'] . 'mgr/widgets/home.panel.js');
		$this->addJavascript($this->msDiscount->config['jsUrl'] . 'mgr/sections/home.js');

		$this->addHtml('<script type="text/javascript">
			Ext.onReady(function() {
				MODx.load({ xtype: "msd-page-home"});
			});
		</script>');
	}


	/**
	 * @return string
	 */
	public function getTemplateFile() {
		return $this->msDiscount->config['templatesPath'] . 'home.tpl';
	}
}
