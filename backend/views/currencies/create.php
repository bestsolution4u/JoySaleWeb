<?php
use yii\helpers\Html;
use conquer\toastr\ToastrWidget;
$this->params['breadcrumbs'][] = ['label' => 'Currencies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
        'model' => $model,
]) ?>