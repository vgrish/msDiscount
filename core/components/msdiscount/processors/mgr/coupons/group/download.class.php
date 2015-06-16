<?php

class msdCouponsGroupDownloadProcessor extends modObjectProcessor {
	public $permission = 'msdiscount_save';


	/**
	 * @return string
	 */
	public function process() {
		if ($ids = $this->getProperty('ids')) {
			$ids = $this->modx->fromJSON($ids);
		}
		if ($this->getProperty('check')) {
			return !empty($ids)
				? $this->success()
				: $this->failure($this->modx->lexicon('msd_err_download_ids'));
		}

		$c = $this->modx->newQuery('msdCoupon', array('group_id:IN' => $ids));
		$c->innerJoin('msdCouponGroup', 'Group');
		$c->select($this->modx->getSelectColumns('msdCoupon', 'msdCoupon'));
		$add = array('name', 'discount', 'begins', 'ends');
		$c->select($this->modx->getSelectColumns('msdCouponGroup', 'Group', '', $add));

		if ($c->prepare() && $c->stmt->execute()) {
			header('Content-Type: application/csv');
			header("Content-Disposition: attachment; filename=\"coupons-" . date('Y-m-d Hi') . ".csv\"");
			$output = fopen('php://output', 'w');

			$header = array_merge(array_keys($this->modx->getFieldMeta('msdCoupon')), $add);
			fputcsv($output, $header, ';');
			while ($row = $c->stmt->fetch(PDO::FETCH_ASSOC)) {
				fputcsv($output, $row, ';');
			}
		}

		return '';
	}

}

return 'msdCouponsGroupDownloadProcessor';