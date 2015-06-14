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
		$modx23 = $this->msDiscount->systemVersion();
		$this->addCss($this->msDiscount->config['cssUrl'] . 'mgr/main.css');
		$this->addCss($this->msDiscount->config['cssUrl'] . 'mgr/bootstrap.buttons.css');
		if (!$modx23) {
			$this->addCss($this->msDiscount->config['cssUrl'] . 'mgr/font-awesome.min.css');
		}
		$this->addJavascript($this->msDiscount->config['jsUrl'] . 'mgr/misc/combos.js');
		$this->addJavascript($this->msDiscount->config['jsUrl'] . 'mgr/misc/utils.js');
		$this->addJavascript($this->msDiscount->config['jsUrl'] . 'mgr/widgets/usergroups.grid.js');
		$this->addJavascript($this->msDiscount->config['jsUrl'] . 'mgr/widgets/productgroups.grid.js');
		$this->addJavascript($this->msDiscount->config['jsUrl'] . 'mgr/widgets/sales.grid.js');
		$this->addJavascript($this->msDiscount->config['jsUrl'] . 'mgr/widgets/check.form.js');
		$this->addJavascript($this->msDiscount->config['jsUrl'] . 'mgr/sections/home.panel.js');

		$this->addHtml('<script type="text/javascript">
			MODx.modx23 = ' . (int)$modx23 . ';
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
