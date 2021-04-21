<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use dosamigos\datepicker\DatePicker;
use yii\widgets\ActiveForm;
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

<div class="content">
                    <div class="row">
                        <div class="col-lg-12 userinfo">
                                                </div>
                    </div>

           
                <div class="container">
                <div id="page-wrapper">
                <div class="row">
    <div class="col-sm-12">
                          
                            </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading"><?=Yii::t('app','Paid banners Log')?>
               
                             </div>
                <!-- /.panel-heading -->
                <div class="panel-body">    



                 <div class="row">
                <div class="col-sm-12 col-md-6">
                <div class="dataTables_length" id="selection-datatable_length">               
                <?=Html::a('<i class="fa fa-plus"></i> '.Yii::t('app','Export'),['admin/promotionexcel', 'start'=>$start, 'end'=>$end, 'type'=>$type],['class' => 'btn btn-info']); ?>   
               </div></div>

                        <div class="col-sm-12 col-md-6 text-right">
                <div id="selection-datatable_filter" class="dataTables_filter">
                <?php $form = ActiveForm::begin([
        'action' => ['admin/paidbanner'],
        'method' => 'get',
    ]); ?>


<input type="date" name="createdDate" data-date-format="MM DD YYYY" value="<?=Yii::$app->request->queryParams['createdDate']?>">

<input type="date" name="enddate" data-date-format="MM DD YYYY" value="<?=Yii::$app->request->queryParams['enddate']?>">
<?= Html::submitButton('GO', ['class' => 'btn btn-info'])?>

  <?php ActiveForm::end(); ?>
                </div> <br><br>
                </div>

                

                <!--<div class="col-sm-12 col-md-6 text-right">
                <div id="selection-datatable_filter" class="dataTables_filter">
                <?php $form = ActiveForm::begin([
        'action' => ['admin/paidbanner'],
        'method' => 'get',
    ]); ?>

<select name="type" class="btn">
<option value="all" <?php if(Yii::$app->request->queryParams['type'] == "adds"){ echo "selected"; } ?>><?php echo Yii::t('app','All'); ?></option>
<option value="adds" <?php if(Yii::$app->request->queryParams['type'] == "adds"){ echo "selected"; } ?>><?php echo Yii::t('app','Ads'); ?></option>
<option value="urgent" <?php if(Yii::$app->request->queryParams['type'] == "urgent"){ echo "selected"; } ?>><?php echo Yii::t('app','Urgent'); ?></option>
</select>
<input type="date" name="createdDate" data-date-format="MM DD YYYY" value="<?=Yii::$app->request->queryParams['createdDate']?>">

<input type="date" name="enddate" data-date-format="MM DD YYYY" value="<?=Yii::$app->request->queryParams['enddate']?>">
<?= Html::submitButton('GO', ['class' => 'btn btn-info'])?>

  <?php ActiveForm::end(); ?>
                </div> <br><br>
                </div>-->




<?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
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
                                        return date("Y-m-d", strtotime($model->createdDate)); },
                                        'headerOptions' =>  ['class' => 'small-input'] ],
                                    
                                ],
                            ]); ?>



            </div>
        </div>       
    </div>  
</div>
   </div>
  </div>
