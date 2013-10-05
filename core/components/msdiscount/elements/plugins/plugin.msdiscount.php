<?php

/** @var msDiscount $msDiscount */
$msDiscount = $modx->getService('msDiscount');

if ($modx->event->name == 'msOnGetProductPrice' && $modx->context->key != 'mgr') {
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
}
elseif ($modx->event->name == 'msOnChangeOrderStatus') {
	/**
	 * Add user to discounts group if they spent required sum for join
	 *
	 */

}