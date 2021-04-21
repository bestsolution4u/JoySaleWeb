<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OrdersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'orderId') ?>

    <?= $form->field($model, 'userId') ?>

    <?= $form->field($model, 'sellerId') ?>

    <?= $form->field($model, 'totalCost') ?>

    <?= $form->field($model, 'totalShipping') ?>

    <?php // echo $form->field($model, 'admincommission') ?>

    <?php // echo $form->field($model, 'discount') ?>

    <?php // echo $form->field($model, 'discountSource') ?>

    <?php // echo $form->field($model, 'orderDate') ?>

    <?php // echo $form->field($model, 'shippingAddress') ?>

    <?php // echo $form->field($model, 'currency') ?>

    <?php // echo $form->field($model, 'sellerPaypalId') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'statusDate') ?>

    <?php // echo $form->field($model, 'trackPayment') ?>

    <?php // echo $form->field($model, 'reviewFlag') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
