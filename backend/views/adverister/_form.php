<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Adverister */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="adverister-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'userid')->textInput() ?>

    <?= $form->field($model, 'webbanner')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'appbanner')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bannerlink')->textInput() ?>

    <?= $form->field($model, 'startdate')->textInput() ?>

    <?= $form->field($model, 'enddate')->textInput() ?>

    <?= $form->field($model, 'totaldays')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'paidstatus')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'tranxId')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'createdDate')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
