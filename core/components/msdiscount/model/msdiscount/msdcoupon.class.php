<?php

class msdCoupon extends xPDOSimpleObject {


	public function remove(array $ancestors = array()) {
		$remove = parent::remove($ancestors);

		/** @var msdCouponGroup $group */
		if ($group = $this->getOne('Group')) {
			$group->updateCounters();
		}

		return $remove;
	}

	public function save($cacheFlag = null) {
		$update = $this->isDirty('active');
		$save = parent::save($cacheFlag);

		/** @var msdCouponGroup $group */
		if ($update && $group = $this->getOne('Group')) {
			$group->updateCounters();
		}

		return $save;
	}


}