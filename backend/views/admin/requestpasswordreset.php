<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

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
			<h1 class="page-header">
		<?php echo Yii::t('app','Admin').' '.Yii::t('app','Profile'); ?>			</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<div class="row">
		<div class="col-lg-12">
        <?php if(Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success" role="alert" id="successMessage">
                <button id="close-error" type="button" class="error close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

      <?php if(Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger" role="alert" id="successMessage">
                <button id="close-error" type="button" class="error close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>
			<div class="panel panel-default">
				<div class="panel-heading"><?php echo Yii::t('app','Change Password'); ?></div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">



							<div class="form">
                            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form',  'options' => [
                'class' => 'form-horizontal form-material','autocomplete' => 'off'
             ]
             ]); ?>
             
			 
			  <?= $form->field($model, 'email')->textInput(['autofocus' => true,'class'=>'form-control', 'value'=>$setting['email'],'readonly'=>true])->label('Password reset instructions will be sent to you! ') ?>
                
           

                    <?= Html::submitButton('Reset', ['class'=>'btn btn-success']) ?>
              


            <?php ActiveForm::end(); ?> 
							</div>
							<!-- form -->
						</div>
					</div>
					<!-- /.row (nested) -->
				</div>
					</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->
				</div>
				