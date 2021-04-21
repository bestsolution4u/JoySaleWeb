<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Orders */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'userId')->textInput() ?>

    <?= $form->field($model, 'sellerId')->textInput() ?>

    <?= $form->field($model, 'totalCost')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'totalShipping')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'admincommission')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'discount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'discountSource')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'orderDate')->textInput() ?>

    <?= $form->field($model, 'shippingAddress')->textInput() ?>

    <?= $form->field($model, 'currency')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sellerPaypalId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'statusDate')->textInput() ?>

    <?= $form->field($model, 'trackPayment')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reviewFlag')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
