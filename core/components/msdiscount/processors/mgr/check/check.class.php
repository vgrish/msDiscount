<?php

class msdCheckDiscountProcessor extends modProcessor {

	public function process() {
		/** @var msDiscount $msDiscount */
		$msDiscount = $this->modx->getService('msDiscount');
		$msDiscount->config['debug'] = true;

		if (!$pid = $this->getProperty('product')) {
			return $this->failure($this->modx->lexicon('msd_check_err_pid'));
		}
		elseif (!$product = $this->modx->getObject('msProductData', $pid)) {
			return $this->failure($this->modx->lexicon('msd_check_err_product'));
		}

		$msDiscount->getNewPrice($pid, $product->price, $this->getProperty('user'), $this->getProperty('date'));
		$log = '<pre>'.implode("\n", $msDiscount->debug).'</pre>';

		return $this->success('', array('log' => $log));
	}

}

return 'msdCheckDiscountProcessor';