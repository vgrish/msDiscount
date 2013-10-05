<?php
/**
 * The base class for msDiscount.
 */

class msDiscount {
	/* @var modX $modx */
	public $modx;
	public $debug = array();

	/**
	 * @param modX $modx
	 * @param array $config
	 */
	function __construct(modX &$modx, array $config = array()) {
		$this->modx =& $modx;

		$corePath = $this->modx->getOption('msdiscount_core_path', $config, $this->modx->getOption('core_path') . 'components/msdiscount/');
		$assetsUrl = $this->modx->getOption('msdiscount_assets_url', $config, $this->modx->getOption('assets_url') . 'components/msdiscount/');
		$connectorUrl = $assetsUrl . 'connector.php';

		$this->config = array_merge(array(
			'assetsUrl' => $assetsUrl,
			'cssUrl' => $assetsUrl . 'css/',
			'jsUrl' => $assetsUrl . 'js/',
			'imagesUrl' => $assetsUrl . 'images/',
			'connectorUrl' => $connectorUrl,

			'corePath' => $corePath,
			'modelPath' => $corePath . 'model/',
			'chunksPath' => $corePath . 'elements/chunks/',
			'templatesPath' => $corePath . 'elements/templates/',
			'chunkSuffix' => '.chunk.tpl',
			'snippetsPath' => $corePath . 'elements/snippets/',
			'processorsPath' => $corePath . 'processors/',
			'debug' => true,
		), $config);

		$this->modx->addPackage('msdiscount', $this->config['modelPath']);
		$this->modx->lexicon->load('msdiscount:default');
	}


	/**
	 * Sanitizes values for processors
	 *
	 * @param $key
	 * @param $value
	 *
	 * @return mixed|string
	 */
	public function sanitize($key, $value) {
		$value = trim($value);

		switch (strtolower(trim($key))) {
			case 'discount':
				$value = preg_replace(array('/[^0-9%,\.]/','/,/'), array('', '.'), $value);
				if (strpos($value, '%') !== false) {
					$value = str_replace('%', '', $value) . '%';
				}
				if (empty($value)) {$value = '0%';}
				break;

			case 'begins':
			case 'ends':
				if (empty($value)) {
					$value = '0000-00-00 00:00:00';
				}
				break;
		}
		return $value;
	}


	/**
	 * Return new product price with discounts
	 *
	 * @param array $data
	 * @param float $price Current price of product
	 * @param modUser $user
	 * @param string $date
	 *
	 * @return float $price New price of product
	 */
	public function getNewPrice($product_id, $price, $user_id = '', $date = '') {
		if (empty($user_id)) {
			$user_id = $this->modx->user->id;
		}

		if (empty($user_id) || empty($product_id)) {
			return false;
		}

		$this->debugMessage('Начальная цена товара: "'.$price.'"');
		$users = $this->getUserGroups($user_id);
		$this->debugMessage('Получены группы пользователя('.$user_id.'): <b>'.count($users).'</b>');
		$products = $this->getProductGroups($product_id);
		$this->debugMessage('Получены группы товара('.$product_id.'): <b>'.count($products).'</b>');
		$sales = $this->getSales($date);
		$this->debugMessage('Получены активные скидки: <b>'.count($sales).'</b>');

		$percent = '0%';	// Discount in percent
		$absolute = 0;		// Discount in absolute value

		/** @TODO Писать отладочную инфу на английском или через лексиконы */

		// Get discount by sale
		foreach ($sales as $sale) {
			if (empty($sale['users']) && empty($sale['products'])) {
				$this->debugMessage("Скидка \"$sale[name]\" действует на все группы");
				$discount = $sale['discount'];
				if (strpos($discount, '%') !== false) {
					if ($discount > $percent) {
						$percent = $discount;
						$this->debugMessage('Cкидка от группы "'.$sale['name'].'": "'.$percent.'"');
					}
				}
				elseif ($discount > $absolute) {
					$absolute = $discount;
					$this->debugMessage('Cкидка от группы "'.$sale['name'].'": "'.$absolute.'"');
				}
			}
			else {
				foreach (array('users', 'products') as $group) {

					/** @TODO сделать проверку совпадения групп юзера и товаров */

					foreach ($sale[$group] as $gid) {
						if (!isset(${$group}[$gid])) {continue;}
						$this->debugMessage("Скидка \"$sale[name]\" действует на группы $group");
						$discount = $sale['discount'];
						if (strpos($discount, '%') !== false) {
							if ($discount > $percent) {
								$percent = $discount;
								$this->debugMessage('Скидка акции для группы '.$group.'('.$gid.'): "'.$percent.'"');
							}
						}
						elseif ($discount > $absolute) {
							$absolute = $discount;
							$this->debugMessage('Скидка акции для группы '.$group.'('.$gid.'): "'.$absolute.'"');
						}
					}
				}
			}
		}

		// Get discount by groups
		foreach (array('users', 'products') as $group) {
			foreach (${$group} as $id => $discount) {
				if (strpos($discount, '%') !== false) {
					if ($discount > $percent) {
						$percent = $discount;
						$this->debugMessage('Персональная скидка группы '.$group.'('.$id.'): "'.$percent.'"');
					}
				}
				elseif ($discount > $absolute) {
					$absolute = $discount;
					$this->debugMessage('Персональная скидка группы '.$group.'('.$id.'): "'.$absolute.'"');
				}
			}
		}

		/*
			var_dump($price, $users, $products, $sales);
			echo '<br/><br/>';
			var_dump($percent, $absolute);
		*/


		if ($percent != '0%') {
			$tmp = ($price / 100) * intval($percent);
			$this->debugMessage('Считаем процент "'.$percent.'" от цены товара "'.$price.'" - выходит "'.$tmp.'"');
			$percent = $tmp;
		}
		$discount = $percent > $absolute
			? $percent
			: $absolute;
		$price -= $discount;

		$this->debugMessage('Сравниваем абсолютную скидку "'.$absolute.'" с посчитанным процентом "'.$percent.'" и выбираем большую: "'.$discount.'"');
		$this->debugMessage('Итоговая стоимость товара "'.$price.'"');

		//echo '<pre>';print_r($this->debug);die;

		return $price;
	}


	/**
	 * Return array with groups of user
	 *
	 * @param int $id
	 *
	 * @return array
	 */
	public function getUserGroups($id = 0) {
		$groups = array();

		if (!empty($id)) {
			$q = $this->modx->newQuery('modUserGroupMember', array('member' => $id));
			$q->leftJoin('msdUserGroup', 'msdUserGroup', 'msdUserGroup.id = modUserGroupMember.user_group');
			$q->select('user_group, IFNULL(`discount`, 0) as discount');
			if ($q->prepare() && $q->stmt->execute()) {
				while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
					//if (!empty($row['discount']) && $row['discount'] != '0%') {
						$groups[$row['user_group']] = $row['discount'];
					//}
				}
			}
		}

		return $groups;
	}


	/**
	 * Return array with groups of product
	 *
	 * @param $id
	 *
	 * @return array
	 */
	public function getProductGroups($id) {
		$groups = array();

		$q = $this->modx->newQuery('modResourceGroupResource', array('document' => $id));
		$q->leftJoin('msdProductGroup', 'msdProductGroup', 'msdProductGroup.id = modResourceGroupResource.document_group');
		$q->select('document_group, IFNULL(`discount`, 0) as discount');

		if ($q->prepare() && $q->stmt->execute()) {
			while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
				//if (!empty($row['discount']) && $row['discount'] != '0%') {
					$groups[$row['document_group']] = $row['discount'];
				//}
			}
		}

		return $groups;
	}



	public function getSales($date = '') {
		$groups = array();
		if (empty($date)) {
			$date = date('Y-m-d H:i:s');
		}
		elseif (is_numeric($date)) {
			$date = date('Y-m-d H:i:s', $date);
		}

		$q = $this->modx->newQuery('msdSale', array('active' => 1));
		$q->leftJoin('msdSaleMember', 'msdSaleMember', 'msdSaleMember.sale_id = msdSale.id');
		$q->orCondition(array(
			'begins:=' => '0000-00-00 00:00:00',
			'begins:<=' => $date,
		), '', 1);
		$q->orCondition(array(
			'ends:=' => '0000-00-00 00:00:00',
			'ends:>=' => $date,
		), '', 2);

		$q->select('id,discount,name,group_id,type');
		if ($q->prepare() && $q->stmt->execute()) {
			while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
				if (!empty($row['discount']) && $row['discount'] != '0%') {
					if (!isset($groups[$row['id']])) {
						$groups[$row['id']] = array(
							'id' => $row['id'],
							'discount' => $row['discount'],
							'name' => $row['name'],
							'users' => array(),
							'products' => array(),
						);
					}
					if (!empty($row['type']) && !empty($row['group_id'])) {
						$groups[$row['id']][$row['type']][] = $row['group_id'];
					}
				}
			}
		}

		return $groups;
	}



	public function debugMessage($message) {
		$this->debug[] = $message;
	}


}
