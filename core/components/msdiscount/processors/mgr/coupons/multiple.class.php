<?php

class msdCouponsMultipleProcessor extends modProcessor {


	/**
	 * @return array|string
	 */
	public function process() {
		if (!$method = $this->getProperty('method', false)) {
			return $this->failure();
		}
		$ids = $this->modx->fromJSON($this->getProperty('ids'));
		if (empty($ids)) {
			return $this->success();
		}

		/** @var msDiscount $msDiscount */
		$msDiscount = $this->modx->getService('msDiscount');

		foreach ($ids as $id) {
			/** @var modProcessorResponse $response */
			$response = $this->modx->runProcessor(
				'mgr/coupons/' . $method,
				array('id' => $id),
				array('processors_path' => $msDiscount->config['processorsPath'])
			);
			if ($response->isError()) {
				return $response->getResponse();
			}
		}

		return $this->success();
	}

}

return 'msdCouponsMultipleProcessor';