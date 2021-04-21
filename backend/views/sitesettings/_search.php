<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SitesettingsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sitesettings-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'smtpEmail') ?>

    <?= $form->field($model, 'smtpPassword') ?>

    <?= $form->field($model, 'smtpPort') ?>

    <?= $form->field($model, 'smtpHost') ?>

    <?php // echo $form->field($model, 'smtpEnable') ?>

    <?php // echo $form->field($model, 'smtpSSL') ?>

    <?php // echo $form->field($model, 'signup_active') ?>

    <?php // echo $form->field($model, 'givingaway') ?>

    <?php // echo $form->field($model, 'socialLoginDetails') ?>

    <?php // echo $form->field($model, 'logo') ?>

    <?php // echo $form->field($model, 'logoDarkVersion') ?>

    <?php // echo $form->field($model, 'sitename') ?>

    <?php // echo $form->field($model, 'metaData') ?>

    <?php // echo $form->field($model, 'default_userimage') ?>

    <?php // echo $form->field($model, 'favicon') ?>

    <?php // echo $form->field($model, 'currency_priority') ?>

    <?php // echo $form->field($model, 'category_priority') ?>

    <?php // echo $form->field($model, 'promotionCurrency') ?>

    <?php // echo $form->field($model, 'urgentPrice') ?>

    <?php // echo $form->field($model, 'searchDistance') ?>

    <?php // echo $form->field($model, 'searchType') ?>

    <?php // echo $form->field($model, 'searchList') ?>

    <?php // echo $form->field($model, 'sitepaymentmodes') ?>

    <?php // echo $form->field($model, 'commission_status') ?>

    <?php // echo $form->field($model, 'paypal_settings') ?>

    <?php // echo $form->field($model, 'braintree_settings') ?>

    <?php // echo $form->field($model, 'braintree_merchant_ids') ?>

    <?php // echo $form->field($model, 'api_settings') ?>

    <?php // echo $form->field($model, 'footer_settings') ?>

    <?php // echo $form->field($model, 'tracking_code') ?>

    <?php // echo $form->field($model, 'googleapikey') ?>

    <?php // echo $form->field($model, 'staticMapApiKey') ?>

    <?php // echo $form->field($model, 'account_sid') ?>

    <?php // echo $form->field($model, 'auth_token') ?>

    <?php // echo $form->field($model, 'sms_number') ?>

    <?php // echo $form->field($model, 'fb_appid') ?>

    <?php // echo $form->field($model, 'fb_secret') ?>

    <?php // echo $form->field($model, 'facebookshare') ?>

    <?php // echo $form->field($model, 'bannerstatus') ?>

    <?php // echo $form->field($model, 'promotionStatus') ?>

    <?php // echo $form->field($model, 'product_autoapprove') ?>

    <?php // echo $form->field($model, 'androidkey') ?>

    <?php // echo $form->field($model, 'bannervideoStatus') ?>

    <?php // echo $form->field($model, 'bannervideo') ?>

    <?php // echo $form->field($model, 'bannervideoposter') ?>

    <?php // echo $form->field($model, 'bannerText') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
