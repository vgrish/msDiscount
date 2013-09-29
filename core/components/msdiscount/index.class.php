<?php

require_once dirname(__FILE__) . '/model/msdiscount/msdiscount.class.php';

/**
 * Class msDiscountMainController
 */
abstract class msDiscountMainController extends modExtraManagerController {
	/** @var msDiscount $msDiscount */
	public $msDiscount;
	/** @var miniShop2 $miniShop2 */
	public $miniShop2;


	/**
	 * @return void
	 */
	public function initialize() {
		if (!include_once MODX_CORE_PATH . 'components/minishop2/model/minishop2/minishop2.class.php') {
			throw new Exception('You must install miniShop2 first');
		}

		$this->msDiscount = new msDiscount($this->modx);
		$this->miniShop2 = new miniShop2($this->modx);

		$this->addJavascript(MODX_MANAGER_URL . 'assets/modext/util/datetime.js');
		$this->addCss($this->msDiscount->config['cssUrl'] . 'mgr/main.css');
		$this->addJavascript($this->msDiscount->config['jsUrl'] . 'mgr/msdiscount.js');

		$this->addJavascript($this->miniShop2->config['jsUrl'] . 'mgr/minishop2.js');
		$this->addJavascript($this->miniShop2->config['jsUrl'] . 'mgr/misc/ms2.utils.js');
		$this->addJavascript($this->miniShop2->config['jsUrl'] . 'mgr/misc/ms2.combo.js');

		$this->addHtml('<script type="text/javascript">
		Ext.onReady(function() {
			miniShop2.config = ' . $this->modx->toJSON($this->miniShop2->config) . ';
			miniShop2.config.connector_url = "' . $this->miniShop2->config['connectorUrl'] . '";
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
