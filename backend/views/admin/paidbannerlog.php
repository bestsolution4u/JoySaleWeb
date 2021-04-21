<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use dosamigos\datepicker\DatePicker;
use yii\widgets\ActiveForm;
use backend\models\BannerapprovedSearch;
$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;


$searchModel = new BannerapprovedSearch();
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
                <h4 class="m-b25 blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Paid banners Logs')?></h4>
            </div>
             <?php $form = ActiveForm::begin([
                  'action' => ['admin/paidbanner'],
                  'method' => 'get',
              ]); ?>
  <div class="d-flex flex-column flex-sm-row m-b40">


                <div class="day d-flex flex-column flex-md-row p-t20">
                       
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
                    <?=Html::a('<i class="fa fa-plus p-r10"></i> '.Yii::t('app','Export'),['admin/paidbannerexcel', 'start'=>$start, 'end'=>$end,'type'=>$type]); ?> 
                </button>  

              
                          
              
               </div>
            </div>
<div class="table-responsive">
                  <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                             
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    [
                                        'header'=>Yii::t('app','Website'),
                                        'headerOptions' => ['style' => 'width:10%'],
                                        'format' =>['image',['width'=>'100','height'=>'auto']],
                                        'value' => function($model) {     
                                            $path = Yii::$app->urlManagerfrontEnd->baseUrl.'/media/banners/';                                  
                                       return $path.$model->bannerimage;
                                     } ,
                                     
                                     ],
                                    
                                     [
                                        'header'=>Yii::t('app','Mobile'),
                                        'headerOptions' => ['style' => 'width:10%'],
                                        'format' =>['image',['width'=>'100','height'=>'auto']],
                                        'value' => function($model) {     
                                            $path = Yii::$app->urlManagerfrontEnd->baseUrl.'/media/banners/';                                  
                                       return $path.$model->appbannerimage;
                                     } ,
                                     
                                     ],
                                     [
                                        'header'=>Yii::t('app','Total Cost'),
                                        'content' => function($model) {
                                          return $model->currency. ' '. $model->totalCost;
                                     } 
                                     ], 
                                  
                                    ['attribute' => 'totaldays','filter'=>false ],
                                
                                     
                                    ['attribute' => 'startdate','filter'=>false ],
                                   
                                    ['attribute' => 'enddate','filter'=>false ],
                                    ['header'=>Yii::t('app','Posted On'),
                                   
                                    'attribute' => 'createdDate',
                                      'filter'=>false,
                                    'content' => function($model) {
                                        return date("m-d-Y", strtotime($model->createdDate)); },
                                        'headerOptions' =>  ['class' => 'small-input'] ],
                                    
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
      
