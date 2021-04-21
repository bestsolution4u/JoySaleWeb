<?php

use yii\helpers\Html;


$this->title = 'Create Products';
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

//print_r($model);
//exit;

?>

<div class="products-create">


 <?= $this->render('_form', ['model'=>$model,
		'parentCategory'=>$parentCategory,'subCategory'=>$subCategory,
		'shippingTime' => $shippingTime, 'countryModel' => $countryModel,'topCurs' => $topCurs,
		'currencies' => $currencies, 'promotionCurrency'=>$promotionCurrency,
		'urgentPrice'=>$urgentPrice, 'promotionDetails'=>$promotionDetails, 'userModel'=>$userModel,
		'geoLocationDetails' => $geoLocationDetails,'shipping_country_code'=>$shipping_country_code,'givingaway'=>$givingaway,'pricerange'=>$pricerange
		]); ?>

</div>

