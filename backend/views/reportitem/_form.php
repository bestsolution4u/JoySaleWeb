<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Reportproducts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reportproducts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'userId')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'category')->textInput() ?>

    <?= $form->field($model, 'subCategory')->textInput() ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <?= $form->field($model, 'currency')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'quantity')->textInput() ?>

    <?= $form->field($model, 'sizeOptions')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'productCondition')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'createdDate')->textInput() ?>

    <?= $form->field($model, 'likeCount')->textInput() ?>

    <?= $form->field($model, 'commentCount')->textInput() ?>

    <?= $form->field($model, 'chatAndBuy')->textInput() ?>

    <?= $form->field($model, 'exchangeToBuy')->textInput() ?>

    <?= $form->field($model, 'instantBuy')->textInput() ?>

    <?= $form->field($model, 'myoffer')->textInput() ?>

    <?= $form->field($model, 'paypalid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shippingTime')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shippingcountry')->textInput() ?>

    <?= $form->field($model, 'shippingCost')->textInput() ?>

    <?= $form->field($model, 'soldItem')->textInput() ?>

    <?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'latitude')->textInput() ?>

    <?= $form->field($model, 'longitude')->textInput() ?>

    <?= $form->field($model, 'likes')->textInput() ?>

    <?= $form->field($model, 'views')->textInput() ?>

    <?= $form->field($model, 'reports')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reportCount')->textInput() ?>

    <?= $form->field($model, 'promotionType')->dropDownList([ 1 => '1', 2 => '2', 3 => '3', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'approvedStatus')->textInput() ?>

    <?= $form->field($model, 'Initial_approve')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
