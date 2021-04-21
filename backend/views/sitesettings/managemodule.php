<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use conquer\toastr\ToastrWidget;
use yii\helpers\Json;
use kartik\alert\Alert;
use common\models\Sitesettings;
$settings = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();

if(!empty($model->sitepaymentmodes)){
    $sitePaymentMode = json_decode($settings->sitepaymentmodes, true);
    $model->exchangePaymentMode = $sitePaymentMode['exchangePaymentMode'];
    $model->buynowPaymentMode = $sitePaymentMode['buynowPaymentMode'];
 }
?>


  <?php $form = ActiveForm::begin(['id'=>'sitesettings-form','options' =>['class' => 'boxShadow p-3 bgWhite m-b20','enctype' => 'multipart/form-data']]); ?>
    <?php 
            if(Yii::$app->session->hasFlash('success')): 
                echo Alert::widget([
                'type' => Alert::TYPE_SUCCESS,
                'body' => Yii::$app->session->getFlash('success'),
                'delay' => 800
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

          <h4 class="m-b25  blueTxtClr p-t10 p-b10"><?php echo  Yii::t('app','Modules').' '.Yii::t('app','Management'); ?></h4>

            <div class="form-group ">
        <label><?php echo Yii::t('app','Exchange Transaction Mode'); ?> </label>
        <div class="m-b20 d-flex">
          <div class="m-r50">
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="exchangePaymentModeenable" name="Sitesettings[exchangePaymentMode]" value="1" <?php if($model->exchangePaymentMode == '1')echo 'checked'?>>
                <label class="custom-control-label" for="exchangePaymentModeenable"><?php echo Yii::t('app','Enable'); ?></label>
            </div>
          </div>
          <div class="custom-control custom-radio">
              <input type="radio" class="custom-control-input" id="exchangePaymentModeDisable" name="Sitesettings[exchangePaymentMode]" value="0" <?php if($model->exchangePaymentMode == '0')echo 'checked'?>>
              <label class="custom-control-label" for="exchangePaymentModeDisable"><?php echo Yii::t('app','Disable'); ?></label>
          </div>
      </div>
    </div>

     
    <div class="form-group ">
        <label><?php echo Yii::t('app','Promotion Module'); ?> </label>
        <div class="m-b20 d-flex">
          <div class="m-r50">
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="promotionStatusenable" name="Sitesettings[promotionStatus]" value="1" <?php if($model->promotionStatus == '1')echo 'checked'?>>
                <label class="custom-control-label" for="promotionStatusenable"><?php echo Yii::t('app','Enable'); ?></label>
            </div>
          </div>
          <div class="custom-control custom-radio">
              <input type="radio" class="custom-control-input" id="promotionStatusDisable" name="Sitesettings[promotionStatus]" value="0" <?php if($model->promotionStatus == '0')echo 'checked'?>>
              <label class="custom-control-label" for="promotionStatusDisable"><?php echo Yii::t('app','Disable'); ?></label>
          </div>
      </div>
    </div>

       <div class="form-group ">
        <label><?php echo Yii::t('app','Giving Away'); ?> </label>
        <div class="m-b20 d-flex">
          <div class="m-r50">
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="givingawayenable" name="Sitesettings[givingaway]" value="yes" <?php if($model->givingaway == 'yes')echo 'checked'?>>
                <label class="custom-control-label" for="givingawayenable"><?php echo Yii::t('app','Enable'); ?></label>
            </div>
          </div>
          <div class="custom-control custom-radio">
              <input type="radio" class="custom-control-input" id="givingawayDisable" name="Sitesettings[givingaway]" value="no" <?php if($model->givingaway == 'no')echo 'checked'?>>
              <label class="custom-control-label" for="givingawayDisable"><?php echo Yii::t('app','Disable'); ?></label>
          </div>
      </div>
    </div>
       <div class="form-group ">
        <label><?php echo Yii::t('app','Paid Banner'); ?> </label>
        <div class="m-b20 d-flex">
          <div class="m-r50">
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="paidbannerstatusenable" name="Sitesettings[paidbannerstatus]" value="1" <?php if($model->paidbannerstatus == '1')echo 'checked'?>>
                <label class="custom-control-label" for="paidbannerstatusenable"><?php echo Yii::t('app','Enable'); ?></label>
            </div>
          </div>
          <div class="custom-control custom-radio">
              <input type="radio" class="custom-control-input" id="paidbannerstatusDisable" name="Sitesettings[paidbannerstatus]" value="0" <?php if($model->paidbannerstatus == '0')echo 'checked'?>>
              <label class="custom-control-label" for="paidbannerstatusDisable"><?php echo Yii::t('app','Disable'); ?></label>
          </div>
      </div>
    </div>
        



                    



  
 


    <!-- site maitenance mode -->

     <div class="form-group ">
        <label><?php echo Yii::t('app','Site Maintenance Mode'); ?> </label>
        <div class="m-b20 d-flex">
          <div class="m-r50">
            <div class="custom-control custom-radio">
                <input type="radio" class="custom-control-input" id="site_maintenance_modeenable" name="Sitesettings[site_maintenance_mode]" value="1" <?php if($model->site_maintenance_mode == '1')echo 'checked'?>>
                <label class="custom-control-label" for="site_maintenance_modeenable"><?php echo Yii::t('app','Enable'); ?></label>
            </div>
          </div>
          <div class="custom-control custom-radio">
              <input type="radio" class="custom-control-input" id="site_maintenance_modeDisable" name="Sitesettings[site_maintenance_mode]" value="0" <?php if($model->site_maintenance_mode == '0')echo 'checked'?>>
              <label class="custom-control-label" for="site_maintenance_modeDisable"><?php echo Yii::t('app','Disable'); ?></label>
          </div>
      </div>
    </div>

      <div class="form-group">
        <label><?php echo Yii::t('app','Maintenance mode Text'); ?> </label>
        <?= $form->field($model, 'maintenance_text')->textInput(['class' => 'form-control','placeholder'=>Yii::t('app','Enter Text')])->label(false); ?>
        <p class="text-danger" id="Sitesettings_maintenance_em_"></p>
      </div>

     <div class="m-t20">
      <?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary align-text-top border-0 m-b10']) ?>
      </div>

  <?php ActiveForm::end(); ?>
  

