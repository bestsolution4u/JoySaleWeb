<?php

use yii\helpers\Html;
use yii\grid\GridView;
use conquer\toastr\ToastrWidget;

$this->title = 'Adveristers';
$this->params['breadcrumbs'][] = $this->title;
?>

      <div class="content">
                    <div class="row">
                        <div class="col-lg-12 userinfo">
                                                </div>
                    </div>                
    <div class="container">
    <div id="page-wrapper">
    <div class="row">
    <div class="col-lg-12">
   </div>
   </div>
    <!-- /.row -->
    <div class="row">

<?php if (Yii::$app->session->hasFlash('warning')) : ?>
      <?= ToastrWidget::widget([
						'type' => 'warning', 'message' => Yii::$app->session->getFlash('warning'),
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
					]); ?>
    <?php endif; ?>
	<?php if (Yii::$app->session->hasFlash('success')) : ?>
      <?= ToastrWidget::widget([
						'type' => 'success', 'message' => Yii::$app->session->getFlash('success'),
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
					]); ?>
    <?php endif; ?>
	<?php if (Yii::$app->session->hasFlash('error')) : ?>
      <?= ToastrWidget::widget([
						'type' => 'error', 'message' => Yii::$app->session->getFlash('error'),
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
					]); ?>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('info')) : ?>
      <?= ToastrWidget::widget([
						'type' => 'info', 'message' => Yii::$app->session->getFlash('info'),
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
					]); ?>
<?php endif; ?>

        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading"><?php echo Yii::t('app','Paid Banner Management') ?>  </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                <div class="table-responsive" id="adverister-grid">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'userid',
            'webbanner',
            'appbanner',
            [
                'header'=>Yii::t('app','Accept'),
                'content' => function($model) {
               //  return Html::a("hi");
               return Html::a(Yii::t('app','Accept'), ['adverister/accept', 'id'=>$model->id],array('class' => "callMobilePayment btn btn-info"));
             } 
             ],
            [
               'header'=>Yii::t('app','Decline'),
               'content' => function($model) {
              //  return Html::a("hi");
              return Html::a(Yii::t('app','Refund'), ['adverister/refund', 'id'=>$model->id],array('class' => "callMobilePayment btn btn-danger"));
            } 
            ],

            // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

           
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
                </div>
              