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
		/** Add user to discounts group if they spent required sum for join */

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
			unset($_SESSION['minishop2']['cart_reload']);
		}
		break;
}