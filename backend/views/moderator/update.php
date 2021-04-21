<?php
use yii\helpers\Html;
?>

<div class="content">
		            <div class="row">
						<div class="col-lg-12 userinfo">
												</div>
					</div>

	<div class="container" class="admin-create">
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
				<?php echo Yii::t('app','Update').' '.Yii::t('app','Moderator'); ?>		</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
	
<div class="form">
<?= $this->render('_update', [
        'model' => $model,'roles'=>$roles,'password'=>$password
    ]) ?>

</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
    </div>