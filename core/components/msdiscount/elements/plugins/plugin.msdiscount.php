<?php

/** @var msDiscount $msDiscount */
$msDiscount = $modx->getService('msDiscount');

switch ($modx->event->name) {

	case 'msOnGetProductPrice':
		if ($modx->context->key == 'mgr') {return;}
		/**
		 * Counts discount of current product for current user, based on rules in msDiscount component
		 * New price must be set in $modx->event->returnedValues['price']
		 *
		 * @var msProductData $product Object with product properties
		 * @var array $data Array with product properties. Can be empty!
		 * @var float $price Current price of product
		 */
		if (!isset($modx->event->returnedValues['price'])) {
			$modx->event->returnedValues['price'] = $price;
		}
		// Get link to product price
		$price = & $modx->event->returnedValues['price'];
		$new_price = $msDiscount->getNewPrice($data['id'], $price);
		if ($new_price !== false) {
			$price = $new_price;
		}
		break;

	case 'msOnChangeOrderStatus':
		/**
		 * Add user to discounts group if he spent required sum for join
		 *
		 * @var msOrder $order
		 * @var integer $status
		 */
		if ($status != 2) {return;}

		/** @var modUser $user */
		if ($user = $order->getOne('User')) {
			if ($profile = $modx->getObject('msCustomerProfile', $user->id)) {
				$spent = $profile->spent;
				if ($spent > 0) {
					$q = $modx->newQuery('msdUserGroup');
					$q->where('joinsum > 0');
					$q->select('id,joinsum');
					if ($q->prepare() && $q->stmt->execute()) {
						$groups = $msDiscount->getUserGroups($user->id);
						while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
							if ($spent > $row['joinsum'] && !isset($groups[$row['id']])) {
								$user->joinGroup((integer) $row['id'], 1);
							}
						}
					}
				}
			}
		}
		break;

	case 'OnWebLogin':
	case 'OnWebLogout':
		/** Set flag for cart reload */
		$_SESSION['minishop2']['cart_reload'] = true;
		break;

	case 'OnLoadWebDocument':
		/**
		 * Recalculate cart of user if flag is set
		 * @var miniShop2 $miniShop2
		 */
		if (empty($_SESSION['minishop2']['cart_reload'])) {return;}

		$miniShop2 = $modx->getService('miniShop2');
		$miniShop2->initialize($modx->context->key);

		$cart = $miniShop2->cart->get();
		if (!empty($cart)) {
			foreach ($cart as $key => $item) {
				/** @var msProduct $product */
				if ($product = $modx->getObject('msProductData', $item['id'])) {
					$cart[$key]['price'] = $product->getPrice();
				}
			}
			$miniShop2->cart->set($cart);
		}
		unset($_SESSION['minishop2']['cart_reload']);
		break;
}