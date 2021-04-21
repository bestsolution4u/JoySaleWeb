<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Sitesettings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sitesettings-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'smtpEmail')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'smtpPassword')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'smtpPort')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'smtpHost')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'smtpEnable')->textInput() ?>

    <?= $form->field($model, 'smtpSSL')->textInput() ?>

    <?= $form->field($model, 'signup_active')->dropDownList([ 'yes' => 'Yes', 'no' => 'No', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'givingaway')->dropDownList([ 'yes' => 'Yes', 'no' => 'No', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'socialLoginDetails')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'logo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'logoDarkVersion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sitename')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'metaData')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'default_userimage')->textInput(['maxlength' => true]) ?>

     <?= $form->field($model, 'default_productimage')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'favicon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'currency_priority')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'category_priority')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'promotionCurrency')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'urgentPrice')->textInput() ?>

    <?= $form->field($model, 'searchDistance')->textInput() ?>

    <?= $form->field($model, 'searchType')->dropDownList([ 'miles' => 'Miles', 'kilometer' => 'Kilometer', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'searchList')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sitepaymentmodes')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'commission_status')->textInput() ?>

    <?= $form->field($model, 'paypal_settings')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'braintree_settings')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'braintree_merchant_ids')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'api_settings')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'footer_settings')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'tracking_code')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'googleapikey')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'staticMapApiKey')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'account_sid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'auth_token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sms_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fb_appid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fb_secret')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'facebookshare')->textInput() ?>

    <?= $form->field($model, 'bannerstatus')->textInput() ?>

    <?= $form->field($model, 'promotionStatus')->textInput() ?>

    <?= $form->field($model, 'product_autoapprove')->textInput() ?>

    <?= $form->field($model, 'androidkey')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bannervideoStatus')->textInput() ?>

    <?= $form->field($model, 'bannervideo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bannervideoposter')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bannerText')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
