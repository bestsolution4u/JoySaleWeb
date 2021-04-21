<?php
use yii\helpers\Html;
$siteSettings = yii::$app->Myclass->getSitesettings();
$this->title = $siteSettings->sitename;
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="content">
		            <div class="row">
						<div class="col-lg-12 userinfo">
												</div>
					</div>

	<div class="container" class="filter-create">
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
		<?php if(Yii::$app->controller->action->id== 'create') {
			   echo Yii::t('app','Create').' '.Yii::t('app','Filters');
			 } else {
				echo Yii::t('app','Updatde').' '.Yii::t('app','Filters');
             } ?>
			   
			   	</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
<div class="filter-create">
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
