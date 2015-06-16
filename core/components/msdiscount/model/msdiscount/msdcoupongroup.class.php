<?php

/**
 * @property int id
 */
class msdCouponGroup extends xPDOSimpleObject {
	protected $_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';


	/**
	 * @param null $cacheFlag
	 *
	 * @return bool
	 */
	public function save($cacheFlag = null) {
		$coupons = abs($this->get('coupons'));

		if (!$this->isNew()) {
			$children = $this->xpdo->getCount('msdCoupon', array('group_id' => $this->id));
			if ($children < $coupons) {
				$generate = $coupons - $children;
			}
			else {
				$generate = 0;
			}
		}
		else {
			$generate = $coupons;
			parent::save($cacheFlag);
		}

		if ($generate > 0) {
			$this->generateCoupons($generate);
		}

		return parent::save();
	}


	/**
	 * @param $number
	 */
	public function generateCoupons($number) {
		$sql = "INSERT INTO {$this->xpdo->getTableName('msdCoupon')} (`group_id`, `code`, `active`) VALUES (?, ?, ?)";
		$query = $this->xpdo->prepare($sql);

		$prefix = $this->get('prefix');
		if (empty($prefix) || !preg_match('#[A-Z0-9]{5}#i', $prefix)) {
			$prefix = 'MS' . sprintf('%03d', $this->id);
			$this->set('prefix', $prefix);
		}
		$chars = str_split($this->_chars);
		$length = count($chars) - 1;
		while ($number > 0) {
			$blocks = array($prefix);
			for ($i = 0; $i < 3; $i++) {
				$idx = $i + 1;
				$blocks[$idx] = '';
				for ($i2 = 0; $i2 < 4; $i2++) {
					$blocks[$idx] .= $chars[rand(0, $length)];
				}
			}

			if ($query->execute(array($this->id, implode('-', $blocks), 1))) {
				$number--;
			}
		}

		$this->updateCounters();
	}


	/**
	 *
	 */
	public function updateCounters() {
		$total = $this->xpdo->getCount('msdCoupon', array('group_id' => $this->id));
		$activated = $this->xpdo->getCount('msdCoupon', array('group_id' => $this->id, 'active' => false));

		$this->set('coupons', $total);
		$this->set('activated', $activated);

		$this->save();
	}


	/**
	 * @param array $ancestors
	 *
	 * @return bool
	 */
	public function remove(array $ancestors = array()) {
		$this->xpdo->removeCollection('msdCoupon', array('group_id' => $this->get('id')));

		return parent::remove($ancestors);
	}

}