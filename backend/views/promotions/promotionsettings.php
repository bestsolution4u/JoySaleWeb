<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Json;
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
			<h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Account').' '.Yii::t('app','Settings'); ?> - <?php echo Yii::t('app','Site Transaction Settings'); ?></h4>

            <?php
                $sitepaymentmodes = json::decode($model->sitepaymentmodes,true);
                $model->exchangePaymentMode = $sitepaymentmodes['exchangePaymentMode'];
            ?>
				
			<div class="form-group ">
				<label><?php echo Yii::t('app','Exchange Transaction Mode'); ?> </label>
				<div class="m-b20 d-flex">
					<div class="m-r50">
						<div class="custom-control custom-radio">
								<input type="radio" class="custom-control-input" id="enable" name="Sitesettings[exchangePaymentMode]" value="1" <?php if($model->exchangePaymentMode == '1')echo 'checked'?>>
								<label class="custom-control-label" for="enable"><?php echo Yii::t('app','Enable'); ?></label>
						</div>
					</div>
					<div class="custom-control custom-radio">
							<input type="radio" class="custom-control-input" id="Disable" name="Sitesettings[exchangePaymentMode]" value="0" <?php if($model->exchangePaymentMode == '0')echo 'checked'?>>
							<label class="custom-control-label" for="Disable"><?php echo Yii::t('app','Disable'); ?></label>
					</div>
				</div>
			</div>
			
			<div class="form-group ">
				<label><?php echo Yii::t('app','Site Promotion Module'); ?> </label>
				<div class="m-b20 d-flex">
					<div class="m-r50">
						<div class="custom-control custom-radio">
								<input type="radio" class="custom-control-input" id="enable1" name="Sitesettings[promotionStatus]" value="1" <?php if($model->promotionStatus == '1')echo 'checked'?>>
								<label class="custom-control-label" for="enable1"><?php echo Yii::t('app','Enable'); ?></label>
						</div>
					</div>
					<div class="custom-control custom-radio">
							<input type="radio" class="custom-control-input" id="Disable1" name="Sitesettings[promotionStatus]" value="0" <?php if($model->promotionStatus == '0')echo 'checked'?>>
							<label class="custom-control-label" for="Disable1"><?php echo Yii::t('app','Disable'); ?></label>
					</div>
				</div>
      </div>
		
		  <div class="m-t20">
			<?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
      </div>
       
							
<?php ActiveForm::end(); ?>
