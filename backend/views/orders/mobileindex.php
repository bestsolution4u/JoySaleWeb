<?php 
use yii\helpers\Html;
use conquer\toastr\ToastrWidget;
use kartik\alert\Alert;
?>

<div class="boxShadow p-t20 p-b20 p-l15 p-r10 bgWhite m-b20">
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
       <div class="d-flex justify-content-between  flex-column flex-sm-row">
            <div>
                <h4 class="m-b25 blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Manage Orders'); ?></h4>
            </div>
                    
            <div class="">
               
            </div>
        </div>	

				<div class="d-flex justify-content-between  flex-column flex-sm-row">
            <div>
            </div>
                    
            <div class="m-b20">			
				<?php
				if( Yii::$app->getRequest()->getQueryParam('status') == 'approved') {
					$aclass = 'btn-success';
				}else {
					$aclass = 'btn-primary';
				}
				if( Yii::$app->getRequest()->getQueryParam('status') == 'pending') {
					$class = 'btn-success';
				}else {
					$class = 'btn-primary';
				}
				if( Yii::$app->getRequest()->getQueryParam('status') == 'shipped') {
					$sclass = 'btn-success';
				}else {
					$sclass = 'btn-primary';
				}
				if( Yii::$app->getRequest()->getQueryParam('status') == 'delivered') {
					$dclass = 'btn-success';
				}else {
					$dclass = 'btn-primary';
				}
				if( Yii::$app->getRequest()->getQueryParam('status') == 'cancelled') {
					$cclass = 'btn-success';
				}else {
					$cclass = 'btn-primary';
				}				
				if( Yii::$app->getRequest()->getQueryParam('status') == 'refunded') {
					$rclass = 'btn-success';
				}else {
					$rclass = 'btn-primary';
				}	
				if( Yii::$app->getRequest()->getQueryParam('status') == 'claimed') {
					$clclass = 'btn-success';
				}else {
					$clclass = 'btn-primary';
				}	
				if( Yii::$app->getRequest()->getQueryParam('status') == 'processing') {
					$cllclass = 'btn-success';
				}else {
					$cllclass = 'btn-primary';
				}
				if( Yii::$app->getRequest()->getQueryParam('status') == '') {
					$oclass = 'btn-success';
				}else {
					$oclass = 'btn-primary';
				}
				echo Html::a(Yii::t('app','New Orders'), ['orders/scroworders','status'=>'pending'],array('class' => "btn btn-sm btn-default pull-right custom-class $class")).'&nbsp;';
				echo Html::a(Yii::t('app','Processing'),['orders/scroworders','status'=>'processing'],array('class' => "btn btn-sm btn-default pull-right custom-class $cllclass")
				).'&nbsp;';	
				echo Html::a(Yii::t('app','Shipped'),['orders/scroworders','status'=>'shipped'],array('class' => "btn btn-sm btn-default pull-right custom-class $sclass",'style'=>"cursor: pointer; font-size: 12px; line-height: 16px;margin-left:5px; ")
				).'&nbsp;';
				echo Html::a(Yii::t('app','Delivered'),['orders/scroworders','status'=>'delivered'],array('class' => "btn btn-sm btn-default pull-right custom-class $dclass")
				).'&nbsp;';
				echo Html::a(Yii::t('app','Cancelled'),['orders/scroworders','status'=>'cancelled'],array('class' => "btn btn-sm btn-default pull-right custom-class $cclass")
				).'&nbsp;';
				echo Html::a(Yii::t('app','Refunded'),['orders/scroworders','status'=>'refunded'],array('class' => "btn btn-sm btn-default pull-right custom-class $rclass")
				).'&nbsp;';			
				echo Html::a(Yii::t('app','Claimed'),['orders/scroworders','status'=>'claimed'],array('class' => "btn btn-sm btn-default pull-right custom-class $clclass")
				).'&nbsp;';	
				
				echo Html::a(Yii::t('app','All Orders'),['orders/scroworders','status'=>''],array('class' => "btn btn-sm btn-default pull-right custom-class $oclass")
				).'&nbsp;';
				echo Html::a(Yii::t('app','Approved'),['orders/scroworders','status'=>'approved'],array('class' => "btn btn-sm btn-default pull-right custom-class $aclass")
				).'&nbsp;';
				?>
</div>
        </div>
				<!-- /.panel-heading -->
				<div class="panel-body table-responsive">
				

				<?php echo $this->render('scroworders',['model'=>$model,'status' => $status,'commissionStatus' => $commissionStatus,'dataProvider'=> $dataProvider]);
				 ?>
</div>

<style>
.custom-class{
	cursor: pointer; font-size: 12px; line-height: 16px;margin-left:5px;margin-bottom: 5px;
}
</style>