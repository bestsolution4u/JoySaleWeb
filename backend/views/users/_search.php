<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UsersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'userId') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'password') ?>

    <?= $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'phonevisible') ?>

    <?php // echo $form->field($model, 'country') ?>

    <?php // echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'state') ?>

    <?php // echo $form->field($model, 'postalcode') ?>

    <?php // echo $form->field($model, 'geolocationDetails') ?>

    <?php // echo $form->field($model, 'userImage') ?>

    <?php // echo $form->field($model, 'userstatus') ?>

    <?php // echo $form->field($model, 'activationStatus') ?>

    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'facebookId') ?>

    <?php // echo $form->field($model, 'fbdetails') ?>

    <?php // echo $form->field($model, 'facebook_session') ?>

    <?php // echo $form->field($model, 'twitterId') ?>

    <?php // echo $form->field($model, 'googleId') ?>

    <?php // echo $form->field($model, 'notificationSettings') ?>

    <?php // echo $form->field($model, 'defaultshipping') ?>

    <?php // echo $form->field($model, 'createdDate') ?>

    <?php // echo $form->field($model, 'lastLoginDate') ?>

    <?php // echo $form->field($model, 'averageRating') ?>

    <?php // echo $form->field($model, 'recently_view_product') ?>

    <?php // echo $form->field($model, 'mobile_verificationcode') ?>

    <?php // echo $form->field($model, 'mobile_status') ?>

    <?php // echo $form->field($model, 'unreadNotification') ?>

    <?php // echo $form->field($model, 'sms_country_code') ?>

    <?php // echo $form->field($model, 'country_code') ?>

    <?php // echo $form->field($model, 'braintree_cid') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
