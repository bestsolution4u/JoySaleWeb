<?php
use yii\helpers\Html;
use yii\grid\GridView;
//use conquer\toastr\ToastrWidget;
$siteSettings = yii::$app->Myclass->getSitesettings();
$this->title = 'Joysale';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
                    <div class="row">
                        <div class="col-lg-12 userinfo"></div>
                  </div>

                <div class="container">
                <div id="page-wrapper">
                <div class="row">
                <div class="col-lg-12" style="text-align: right;">
<?=Html::a('<i class="fa fa-plus"></i> '.Yii::t('app','Add').' '.Yii::t('app','Filters'),['filter/add','id'=>$_GET['id']],['class' => 'btn btn-info']); ?>&nbsp;&nbsp;&nbsp;


<br><br>
   </div>

    </div>
    <!-- /.row -->
    <div class="row">
    <?php if(Yii::$app->session->hasFlash('success')): ?>
    
    <?php endif; ?>


        <?php if(Yii::$app->session->hasFlash('warning')): 
            echo Yii::$app->session->getFlash('warning');
        /*
        ?>
        <?=ToastrWidget::widget(['type' => 'warning', 'message'=>Yii::$app->session->getFlash('warning'),
"closeButton" => true,
"debug" => false,
"newestOnTop" => false,
"progressBar" => false,
"positionClass" => ToastrWidget::POSITION_TOP_RIGHT,
"preventDuplicates" => false,
"onclick" => null,
"showDuration" => "300",
"hideDuration" => "1000",
"timeOut" => "5000",
"extendedTimeOut" => "1000",
"showEasing" => "swing",
"hideEasing" => "linear",
"showMethod" => "fadeIn",
"hideMethod" => "fadeOut"
]);?>
    <?php */
    endif; ?>
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading"><?php echo Yii::t('app','Filters Management')?>
                <?php  echo Html::a( '<i class="fa  fa-angle-double-left"></i> '.Yii::t('app','Back').'', Yii::$app->request->referrer,['class'=>'label light  text-sm text-dark pull-right' , 'style'=>['font-size' => '12px']]); ?>

                </div>
                <div class="panel-body">


                <div class="table-responsive" id="categories-grid">
  

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'type',
            //'value:ntext',
            //'isRequired',
            //'status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>



                </div>
             </div>
          </div>
       </div>
  </div>
</div>
              
