<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use conquer\toastr\ToastrWidget;
use kartik\alert\Alert;
?>

<?php $form = ActiveForm::begin(['id' => 'users-profile-form','options' =>['class' => 'boxShadow p-3 bgWhite m-b20']]); ?>
        <?php 
            if(Yii::$app->session->hasFlash('success')): 
                echo Alert::widget([
                'type' => Alert::TYPE_SUCCESS,
                'body' => Yii::$app->session->getFlash('success'),
                'delay' => 8000
                ]); 
            endif; 

            if(Yii::$app->session->hasFlash('error')): 
                echo Alert::widget([
                'type' => Alert::TYPE_DANGER,
                'body' => Yii::$app->session->getFlash('error'),
                'delay' => 8000
                ]); 
            endif; 
        ?>
        <h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Admin').' '.Yii::t('app','Profile'); ?>	  -  <?php echo Yii::t('app','Change Password'); ?></h4>
        <div class="form-group">
            <label><?php echo Yii::t('app','Current Password') ?> </label>	
            <?= $form->field($model, 'oldpass')->passwordInput(['class'=>'form-control','placeholder'=>Yii::t('app','Enter your current password')])->label(false) ?>
        </div>
        <div class="form-group">
			<label><?php echo Yii::t('app','New Password') ?> </label>	
			<?= $form->field($model, 'password')->passwordInput(['class'=>'form-control','placeholder'=>Yii::t('app','Enter your new password')])->label(false) ?>
        </div>
        <div class="form-group">
			<label><?php echo Yii::t('app','Confirm Password') ?> </label>	
			<?= $form->field($model, 'confirm_password')->passwordInput(['class'=>'form-control','placeholder'=>Yii::t('app','Enter re-enter your new password')])->label(false) ?>
		</div>
		
		<div class="m-t20">
			<?= Html::submitButton(Yii::t('app','Reset'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
        </div>
       
							
<?php ActiveForm::end(); ?>