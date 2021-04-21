<?php

use yii\helpers\Html;
use yii\grid\GridView;
use dosamigos\datepicker\DatePicker;
use backend\models\OrdersSearchlog;
use yii\widgets\ActiveForm;

$searchModel = new OrdersSearchlog();
$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;


$start= Yii::$app->request->queryParams['createdDate'];
$end= Yii::$app->request->queryParams['enddate'];
if (isset(Yii::$app->request->queryParams['type'])) {
   $type= Yii::$app->request->queryParams['type'];
}
else
{
    $type='all';
}
?>


<div class="boxShadow p-t20 p-b20 p-l15 p-r10 bgWhite m-b20">
      
       <div class="d-flex justify-content-between  flex-column flex-sm-row">
            <div>
                <h4 class="m-b25 blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Promotion Logs')?></h4>
            </div>

                       <?php $form = ActiveForm::begin([
                  'action' => ['admin/promotion'],
                  'method' => 'get',
              ]); ?>
  <div class="d-flex flex-column flex-sm-row m-b40">
      <div class="day d-flex flex-column flex-md-row p-t20 ">
                       
                        <div class="m-l30 align-self-md-center">
              <select name="type" class="form-control selbox ">
                <option value="all" <?php if(Yii::$app->request->queryParams['type'] == "adds"){ echo "selected"; } ?>><?php echo Yii::t('app','All'); ?></option>
                <option value="adds" <?php if(Yii::$app->request->queryParams['type'] == "adds"){ echo "selected"; } ?>><?php echo Yii::t('app','Ads'); ?></option>
                <option value="urgent" <?php if(Yii::$app->request->queryParams['type'] == "urgent"){ echo "selected"; } ?>><?php echo Yii::t('app','Urgent'); ?></option>
                </select></div></div>

                <div class="day d-flex flex-column flex-md-row p-t20 ">
                       
                        <div class="m-l30 align-self-md-center">
                            <div role="wrapper" class="gj-datepicker gj-datepicker-bootstrap gj-unselectable input-group" style="width: 276px;">    <?= DatePicker::widget([
    'name' => 'createdDate',
    'options' => ['placeholder' => 'Select date'],
    'value' =>  Yii::$app->request->queryParams["createdDate"],
    'template' => '{addon}{input}',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
]);?></div>
                        </div>
    
                    </div>
                    <div class="day d-flex flex-column flex-md-row p-t20 p-r20">
                         
                            <div class="m-l30 align-self-md-center">
                                <div role="wrapper" class="gj-datepicker gj-datepicker-bootstrap gj-unselectable input-group" style="width: 276px;">  <?= DatePicker::widget([
    'name' => 'enddate',
    'options' => ['placeholder' => 'Select date'],
    'value' =>  Yii::$app->request->queryParams["enddate"],
    'template' => '{addon}{input}',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
]);?></div>
                            </div>
        
                        </div>
                             <?= Html::submitButton('GO', ['class' => 'btn btn-primary align-text-top border-0 m-t20'])?>
           </div>

      

            <?php ActiveForm::end(); ?>
         
           
         
        </div>


  <div class="d-flex flex-column flex-sm-row m-b40">
            <div class="dataTables_length" id="selection-datatable_length">   
                <button class='btn btn-primary align-text-top border-0 m-b10'>            
                <?=Html::a('<i class="fa fa-plus"></i> '.Yii::t('app','Export'),['admin/promotionexcel', 'start'=>$start, 'end'=>$end, 'type'=>$type]); ?>  
                  </button>       
               </div>

             </div>
        <div class="table-responsive">
            <?= GridView::widget([
        'dataProvider' => $dataProvider,
     
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'productId',
            [
                'attribute'=>'userId',
               'label' => Yii::t('app','User Name'),
                'format' => 'raw',
                 'value' => function ($model) {                     
                    return  $model->getUserName();                 
                 },
                 'headerOptions' => array('class'=>'userfilter'),'filter'=>false,
                 'enableSorting'=>TRUE
            
                ],
            'promotionPrice',
      
            [
                'attribute'=>'promotionName',
               'label' => Yii::t('app','Type'),
                'format' => 'raw',
                      
                ],
            
            [
                'attribute'=>'createdDate',
                'label' => Yii::t('app','Created Date'),
                'value' => function ($model) {                     
                       return  $model->getCreatedDate();                 
                    },
                    'format'=>['DateTime','php:m-d-Y'],
                'headerOptions' => ['class' => 'small-input','style' => 'width:20%'],'enableSorting'=>TRUE,'filter'=>false],

           
        ],
    ]); ?>
</div>
            </div>
<style type="text/css">
  .selbox
  {
    width: initial;
    display: inline-block !important;
  }
  .gj-datepicker
                {
                   width: 200px !important;
                }
           
</style>
    
