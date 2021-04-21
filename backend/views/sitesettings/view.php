<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Sitesettings */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sitesettings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sitesettings-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'smtpEmail:email',
            'smtpPassword',
            'smtpPort',
            'smtpHost',
            'smtpEnable',
            'smtpSSL',
            'signup_active',
            'givingaway',
            'socialLoginDetails:ntext',
            'logo',
            'logoDarkVersion',
            'sitename',
            'metaData:ntext',
            'default_userimage',
            'default_productimage',
            'favicon',
            'currency_priority:ntext',
            'category_priority:ntext',
            'promotionCurrency:ntext',
            'urgentPrice',
            'searchDistance',
            'searchType',
            'searchList',
            'sitepaymentmodes',
            'commission_status',
            'paypal_settings:ntext',
            'braintree_settings',
            'braintree_merchant_ids:ntext',
            'api_settings:ntext',
            'footer_settings:ntext',
            'tracking_code:ntext',
            'googleapikey',
            'staticMapApiKey',
            'account_sid',
            'auth_token',
            'sms_number',
            'fb_appid',
            'fb_secret',
            'facebookshare',
            'bannerstatus',
            'promotionStatus',
            'product_autoapprove',
            'androidkey',
            'bannervideoStatus',
            'bannervideo',
            'bannervideoposter',
            'bannerText',
        ],
    ]) ?>

</div>
