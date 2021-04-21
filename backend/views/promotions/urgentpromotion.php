<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use conquer\toastr\ToastrWidget;
?>
<?php $form = ActiveForm::begin(['options' => ['class' => 'boxShadow p-3 bgWhite m-b20','onsubmit' => 'return check_details()']]); ?>

        <div class="d-flex justify-content-between  flex-column flex-sm-row">
            <h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Urgent').' '.Yii::t('app','Promotion'); ?>  -  <?php echo Yii::t('app','Set').' '.Yii::t('app','Price'); ?></h4>
            <div class="">
                    <button class='btn btn-primary align-text-top border-0 m-b10'>
                        <?=Html::a('<i class="fa fa-angle-double-left  p-r10"></i> '.Yii::t('app','Back'),['promotions/index']); ?> 
                    </button> 
            </div>
        </div>        


        <?php $placeholder = explode("-",$settings->promotionCurrency);?>

		<div class="form-group">
			<label><?php echo $promotionCurrency[0]; ?> </label>	<span class="required" style="color: red;"> * </span>
			<input type="text" class="form-control" maxlength="6" name="urgentprice" placeholder="<?php echo $placeholder[1];?>" id="urgentprice" value="<?php echo $settings->urgentPrice;?>" onkeypress="return isNumber(event)">
			<p class="text-danger" id="urgentpriceError"></p>
	  	</div>
	  
		<div class="m-t20">
			<?= Html::submitButton(Yii::t('app','Set').' '.Yii::t('app','Price'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
		</div>
       
							
<?php ActiveForm::end(); ?>
