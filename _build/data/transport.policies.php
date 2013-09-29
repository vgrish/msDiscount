<?php

$policies = array();

/* @var modAccessPolicy $policy */
$policy= $modx->newObject('modAccessPolicy');
$policy->fromArray(array (
	'name' => 'msDiscountManagerPolicy',
	'description' => 'A policy for create and update msDiscount items.',
	'parent' => 0,
	'class' => '',
	'lexicon' => PKG_NAME_LOWER . ':permissions',
	'data' => json_encode(array(
		'msdiscount_save' => true,
		'msdiscount_view' => true,
	))
), '', true, true);

$policies[] = $policy;

return $policies;
