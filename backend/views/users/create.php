<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Users */

$this->title = 'Create Users';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
        'model' => $model,
    ]) ?>

