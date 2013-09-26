<?php

require_once dirname(__FILE__) . '/model/msdiscount/msdiscount.class.php';

/**
 * Class msDiscountMainController
 */
abstract class msDiscountMainController extends modExtraManagerController {
	/** @var msDiscount $msDiscount */
	public $msDiscount;


	/**
	 * @return void
	 */
	public function initialize() {
		$this->msDiscount = new msDiscount($this->modx);

		$this->modx->regClientCSS($this->msDiscount->config['cssUrl'] . 'mgr/main.css');
		$this->modx->regClientStartupScript($this->msDiscount->config['jsUrl'] . 'mgr/msdiscount.js');
		$this->modx->regClientStartupHTMLBlock('<script type="text/javascript">
		Ext.onReady(function() {
			msDiscount.config = ' . $this->modx->toJSON($this->msDiscount->config) . ';
			msDiscount.config.connector_url = "' . $this->msDiscount->config['connectorUrl'] . '";
		});
		</script>');

		parent::initialize();
	}


	/**
	 * @return array
	 */
	public function getLanguageTopics() {
		return array('msdiscount:default');
	}


	/**
	 * @return bool
	 */
	public function checkPermissions() {
		return true;
	}
}


/**
 * Class IndexManagerController
 */
class IndexManagerController extends msDiscountMainController {

	/**
	 * @return string
	 */
	public static function getDefaultController() {
		return 'home';
	}
}
