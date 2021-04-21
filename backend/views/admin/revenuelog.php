<?php

use yii\helpers\Html;
use yii\grid\GridView;
use dosamigos\datepicker\DatePicker;
use backend\models\OrdersSearchlog;
use yii\widgets\ActiveForm;
use conquer\toastr\ToastrWidget;
use kartik\alert\Alert;

$searchModel = new OrdersSearchlog();
$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;


$start= Yii::$app->request->queryParams['orderDate'];
$end= Yii::$app->request->queryParams['enddate'];
?>

<div class="boxShadow p-t20 p-b20 p-l15 p-r10 bgWhite m-b20">
      
       <div class="d-flex justify-content-between  flex-column flex-sm-row">
            <div>
                <h4 class="m-b25 blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Buynow Revenue Logs')?></h4>
            </div>


                        <?php $form = ActiveForm::begin([
                  'action' => ['admin/revenuelog'],
                  'method' => 'get',
              ]); ?>
  <div class="d-flex flex-column flex-sm-row m-b40">


                <div class="day d-flex flex-column flex-md-row p-t20 ">
                       
                        <div class="m-l30 align-self-md-center">
                            <div role="wrapper" class="gj-datepicker gj-datepicker-bootstrap gj-unselectable input-group" style="width: 276px;">    <?= DatePicker::widget([
    'name' => 'orderDate',
    'options' => ['placeholder' => 'Select date'],
    'value' =>  Yii::$app->request->queryParams["orderDate"],
    'template' => '{addon}{input}',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
           
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
                    <?=Html::a('<i class="fa fa-plus p-r10"></i> '.Yii::t('app','Export'),['admin/exportexcel', 'start'=>$start, 'end'=>$end]); ?> 
                </button>               
              
               </div>
            </div>







             <?php
            /*   <div class="col-md-4 offset-md-4 pull-right">
            
                <?=Html::a('<i class="fa fa-plus"></i> '.Yii::t('app','Export'),['admin/exportexcel', 'start'=>$start, 'end'=>$end],['class' => 'btn btn-info pull-right']); ?>   
             
               
    <?php $form = ActiveForm::begin([
        'action' => ['admin/revenuelog'],
        'method' => 'get',
    ]); ?>

     
<div class="btn-group">
<div class="btn-group pull-right">

<input type="date" name="orderDate" data-date-format="MM DD YYYY" value="<?=Yii::$app->request->queryParams['orderDate']?>" class="form-control">

<input type="date" name="enddate" data-date-format="MM DD YYYY" value="<?=Yii::$app->request->queryParams['enddate']?>" class="form-control">

<?= Html::submitButton('GO', ['class' => 'btn btn-info'])?>

</div>


            </div>
    <?php ActiveForm::end(); ?>
</div> */ ?>

<div class="table-responsive">
 <?= GridView::widget([
        'dataProvider' => $dataProvider,
       
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            ['attribute' => 'orderId','label' => Yii::t('app','Order ID'),'headerOptions' => ['class' => 'small-input'], 'filter'=>false],
            [
                'attribute'=>'userId',
               'label' => Yii::t('app','Buyer'),
                'format' => 'raw',
                 'value' => function ($model) {                     
                    return  $model->getUserName();                 
                 },
                 'headerOptions' => array('class'=>'userfilter'),'filter'=>false,
                 'enableSorting'=>TRUE
            
                ],
                ['attribute' => 'sellerId','label' => Yii::t('app','Seller'),'value'=>function ($model) {                     
                    return  $model->getSellerName();                 
                 },'filter'=>false,'enableSorting'=>TRUE
                ],

                 ['attribute' =>'Commission','label' => Yii::t('app','Commission'),'value'=>function ($model) {
                     return  $model->getCommission();
                  },'filter'=>false,'headerOptions' => ['class' => 'small-input']],
                  ['attribute' => 'totalCost','filter'=>false,'label' => Yii::t('app','Total Cost'),'value' => function ($model) {                     
        return  $model->getItemcost();                 
     },'headerOptions' =>  ['class' => 'small-input'],'enableSorting'=>TRUE],
                
            [
                'attribute'=>'orderDate',
                'label' => Yii::t('app','Order Date'),
                'value' => function ($model) {                     
                       return  $model->getCreatedDate();                 
                    },
                    'format'=>['DateTime','php:m-d-Y'],
               
                    'filter'=>DatePicker::widget([
                   'name' => 'orderDate',
                   'value' => isset($_GET['orderDate']) ? $_GET['orderDate'] : '',
                   'template' => '{addon}{input}',
                       'clientOptions' => [
                           'autoclose' => true,
                           'format' => 'mm-dd-yyyy'
                       ]
               ]),'headerOptions' => ['class' => 'small-input','style' => 'width:20%'],'enableSorting'=>TRUE,'filter'=>false],

         


                ['class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'header'=>Yii::t('app', 'View'),
                    'buttons' => [

                        'view' => function ($url, $model) {
                            return Html::a('<button class="btn btn-primary align-text-top border-0"><i class="fa fa-eye"></i></button>',array('orders/view', 'id'=>$model->orderId), [
                                    'title' => Yii::t('app', 'View'),
                                    'data-method' => 'post', 'data-pjax' => '0',
                            ]);
                        }
                    ],
            ],

           
        ],
    ]); ?>
</div>

            </div>
        <style type="text/css">
                .gj-datepicker
                {
                    width: 200px !important;
                }
            </style>