<?php
use yii\helpers\Html;
use conquer\toastr\ToastrWidget;
$this->params['breadcrumbs'][] = ['label' => 'Banners', 'url' => ['index']];
?>

<?= $this->render('_form', [
        'model' => $model,
]) ?>


