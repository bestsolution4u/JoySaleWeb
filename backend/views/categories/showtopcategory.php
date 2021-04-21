

						
<?php

use common\models\Categories;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\Request;
use conquer\toastr\ToastrWidget;
use kartik\alert\Alert;
?>

									<div class="wide form">
									<?php
										$form = ActiveForm::begin(['options' => ['class' => 'boxShadow p-3 bgWhite m-b20','enctype' => 'multipart/form-data']]);

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
									<h4 class="m-b25  blueTxtClr p-t10 p-b10"> <?php echo Yii::t('app','Set Top Category').' '.Yii::t('app','Add Priority'); ?></h4>
									<div class="form-group" style="border: 1px solid #eee;padding:10px;">
										<input type="hidden" name="catecount" id="catecount" value="<?= $categoryCount; ?>">
										<input type="text" name="Sitesettings[priority]" class="drag-share tagInputter" value="<?=  $categorylist; ?>">
									</div>
									<div class="btn-block">
										 <?= Html::submitButton(Yii::t('app','Set').' '.Yii::t('app','Priority'), ['class' => 'btn btn-primary']) ?>
										</div>
									<?php ActiveForm::end(); ?>
									</div>


