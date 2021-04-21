<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use conquer\toastr\ToastrWidget;
use kartik\alert\Alert;
?>

<?php $form = ActiveForm::begin(['options' =>['class' => 'boxShadow p-3 bgWhite m-b20']]); ?>
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
        <h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Admin').' '.Yii::t('app','Profile'); ?>  - <?php echo Yii::t('app','Edit').' '.Yii::t('app','Profile'); ?></h4>
        <div class="form-group">
			<label><?php echo Yii::t('app','Email') ?> </label>	
			<?= $form->field($model, 'username')->textInput(['class' => 'form-control','maxlength' => true,'required'=>'required','placeholder'=>Yii::t('app','Enter Email')])->label(false); ?>
        </div>
        <div class="form-group">
			<label><?php echo Yii::t('app','Name') ?> </label>	
			<?= $form->field($model, 'name')->textInput(['maxlength' => true,'required'=>'required','class' => 'form-control','placeholder'=>Yii::t('app','Enter Name')])->label(false); ?>
		</div>
		
		<div class="m-t20">
			<?= Html::submitButton($model->isNewRecord ? Yii::t('app','Create') : Yii::t('app','Save'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
        </div>
       
							
<?php ActiveForm::end(); ?>

