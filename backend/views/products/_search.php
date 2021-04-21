<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ProductsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'productId') ?>

    <?= $form->field($model, 'userId') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'category') ?>

    <?php // echo $form->field($model, 'subCategory') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'currency') ?>

    <?php // echo $form->field($model, 'quantity') ?>

    <?php // echo $form->field($model, 'sizeOptions') ?>

    <?php // echo $form->field($model, 'productCondition') ?>

    <?php // echo $form->field($model, 'createdDate') ?>

    <?php // echo $form->field($model, 'likeCount') ?>

    <?php // echo $form->field($model, 'commentCount') ?>

    <?php // echo $form->field($model, 'chatAndBuy') ?>

    <?php // echo $form->field($model, 'exchangeToBuy') ?>

    <?php // echo $form->field($model, 'instantBuy') ?>

    <?php // echo $form->field($model, 'myoffer') ?>

    <?php // echo $form->field($model, 'paypalid') ?>

    <?php // echo $form->field($model, 'shippingTime') ?>

    <?php // echo $form->field($model, 'shippingcountry') ?>

    <?php // echo $form->field($model, 'shippingCost') ?>

    <?php // echo $form->field($model, 'soldItem') ?>

    <?php // echo $form->field($model, 'location') ?>

    <?php // echo $form->field($model, 'latitude') ?>

    <?php // echo $form->field($model, 'longitude') ?>

    <?php // echo $form->field($model, 'likes') ?>

    <?php // echo $form->field($model, 'views') ?>

    <?php // echo $form->field($model, 'reports') ?>

    <?php // echo $form->field($model, 'reportCount') ?>

    <?php // echo $form->field($model, 'promotionType') ?>

    <?php // echo $form->field($model, 'approvedStatus') ?>

    <?php // echo $form->field($model, 'Initial_approve') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
