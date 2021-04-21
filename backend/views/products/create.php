<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Products */

$this->title = 'Create Products';
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
		            <div class="row">
						<div class="col-lg-12 userinfo">
												</div>
					</div>

                    				<div class="container">
				<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Add Promotion</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
                Add Promotion	</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
<div class="products-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
