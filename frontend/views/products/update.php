<?php
/* @var $this ProductsController */
/* @var $model Products */

$this->params['breadcrumbs'][]=[
	'Products'=>['index'],
$model->name=>['view','id'=>$model->productId],
	'Update',
];

// $this->menu=array(
// array('label'=>'List Products', 'url'=>array('index')),
// array('label'=>'Create Products', 'url'=>array('create')),
// array('label'=>'View Products', 'url'=>array('view', 'id'=>$model->productId)),
// array('label'=>'Manage Products', 'url'=>array('admin')),
// );
?>

<!-- <h1 class="add-head-container fa fa-pencil fa-2x">
	<?php //echo Yii::t('app','Edit sale'); ?>
</h1> -->

	<?php

	echo $this->render('_updateform', array('model'=>$model,
		'attributes'=>$attributes,
		'parentCategory'=>$parentCategory,'subCategory'=>$subCategory,'sub_subCategory' => $sub_subCategory,'photos' => $photos,
		'options'=>$options, 'shippingTime' => $shippingTime, 'countryModel' => $countryModel, 'topCurs' => $topCurs,'currencies' => $currencies, 'givingaway'=>$givingaway, 'promotionCurrency'=>$promotionCurrency,
				'urgentPrice'=>$urgentPrice, 'promotionDetails'=>$promotionDetails,'plen' => $plen,'Filtermodel'=>$Filtermodel,'shipping_country_code'=>$shipping_country_code,'sub_cat_name' => $sub_cat_name,'pricerange'=>$pricerange)); ?>