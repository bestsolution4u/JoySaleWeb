<?php
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	use conquer\toastr\ToastrWidget;
	use kartik\alert\Alert;
?>

<?php $form = ActiveForm::begin(['id'=>'sitesettings-form','options' =>['class' => 'boxShadow p-3 bgWhite m-b20']]); ?>
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
			<h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Site Settings'); ?> - <?php echo Yii::t('app','API Settings'); ?></h4>
				
			<div class="form-group">
        <label><?php echo Yii::t('app','API Username'); ?> </label>
				<?= $form->field($model, 'apiUsername')->textInput(['class' => 'form-control','maxlength' => true,'placeholder'=>Yii::t('app','Enter api user name')])->label(false); ?>
				<p class="text-danger" id="Sitesettings_apiUsername_em_"></p>
      </div>

      <div class="form-group" style="position:relative">
        <label><?php echo Yii::t('app','API Password'); ?> </label>
          <a href="javascript:void(0);" class="eye-icon" onclick="return showapipassword();">
          <i class="show-button fa fa-eye fa-fw"></i>
        </a>
        <div style="position:relative">
				<?= $form->field($model, 'apiPassword')->passwordInput(['maxlength' => true,'class' => 'form-control pull-left m-b20 w-290','id' => 'Sitesettings_apiPassword','readonly'=>'true'])->label(false) ?>	
				<?= $form->field($model, 'apiPassword')->textInput(['maxlength' => true,'class' => 'form-control passwordText m-b20 w-290','id' => 'show_apipassword','style' => 'display:none;float:left;'])->label(false) ?>
			
          </div>
				<input class="btn btn-primary btn-generate-pwd generate" name="yt0" type="button" value="<?php echo Yii::t('app','Generate Password') ?>" data-toggle="modal" data-target="#generate"/>
				<div class="credentials-action">
					<?php if ($makeDefault == 0) { ?>
							<?=Html::a(Yii::t('app','Restore Default'), ['sitesettings/restorapikey'],['class'=>'btn btn-fw btn-success m-t30 btn-center','style' => 'display:table;'])?>
					<?php } ?>
				</div>
				<p class="text-danger" id="Sitesettings_apiPassword_em_"></p>
      </div>
		
		  <div class="m-t20">
			<?= Html::submitButton(Yii::t('app','Save as new'), ['class' => 'btn btn-primary align-text-top border-0 m-b10 btn-center']) ?>
      </div>

			<div class="modal fade" id="generate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content w-75 mx-auto text-center">
                
                            <div class="modal-body"><?=Yii::t('app','Are you sure, you want to change password?')?>
                            </div>
                            <div class="m-t20 m-b20 text-center justify-content-center">
                             
                              <button type="button" id="generatepass" class="btn btn-primary m-r20"><a href="#" style="color:#fff;" onclick="generateapipassword()"><?=Yii::t('app','Okay')?></a></button>
                              <button type="button" class="btn btn-danger" data-dismiss="modal"><?=Yii::t('app','Cancel')?></button>
                            </div>
                          </div>
                        </div>
                      </div>
       
							
<?php ActiveForm::end(); ?>
