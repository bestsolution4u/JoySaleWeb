<?php

use yii\helpers\Html;
use conquer\toastr\ToastrWidget;

$this->params['breadcrumbs'][] = ['label' => 'Banners', 'url' => ['index']];

?>
<div class="content">
		            <div class="row">
						<div class="col-lg-12 userinfo">
												</div>
					</div>

	<div class="container" class="users-create">
				<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"></h1>
		</div>
	</div>
	<div class="row">
  
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
               <?php echo Yii::t('app','Add').' '.Yii::t('app','Banner'); ?>
			     </div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
<div class="banners-create">

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

