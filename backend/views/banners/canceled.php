<?php

use yii\helpers\Html;
use yii\grid\GridView;
use conquer\toastr\ToastrWidget;

$this->params['breadcrumbs'][] = $this->title;
?>
<?php $currency = yii::$app->Myclass->getDbCurrencyList(); ?>

<div class="content">
    <div class="row">
        <div class="col-lg-12 userinfo">
        </div>
    </div>

    <div class="container">
        <div id="page-wrapper">
            <div class="row">

              <div class="col-lg-6">
     		

              </div>
                <div class="col-lg-6" style="text-align: right;">
                <?= Html::a(Yii::t('app', 'View cancelled banner'), ['banners/paidbanner'], ['class' => 'btn btn-info']); ?>&nbsp;&nbsp;&nbsp;

                    <br><br>
                </div>

            </div>
            
            <div class="row">
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
                <div class="col-lg-12">
                    <div class="panel panel-default col-xs-12 no-hor-padding">
                        <div class="panel-heading table-responsive"><?php echo Yii::t('app', 'Approved Banner Management') ?></div>

                     <div class="panel-body col-xs-12 hor-padding">



                            <div class="table-responsive" id="banners-grid">
                               <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],


                                    'bannerimage',
                                    'appbannerimage',
                                    'bannerurl:ntext',
                                    'startdate',
                                    'enddate',
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{delete}',
                                        'headerOptions' => ['style' => 'width:15%;text-align:center;'],
                                        'contentOptions' => ['style' => 'text-align:center;'],
                                        'header' => Yii::t('app', 'Action'),
                                        'buttons' => [


                                            'view' => function ($url, $model) {

                                                return Html::a('<span class="icon-magnifier"></span>', $url, [
                                                    'title' => Yii::t('app', Yii::t('app', 'View')),
                                                    'data-method' => 'post', 'data-pjax' => '0',
                                                ]);
                                            },

                                            'update' => function ($url, $model) {

                                                return Html::a('<span class="icon-note"></span>', $url, [
                                                    'title' => Yii::t('app', 'Update'),
                                                    'data-method' => 'post', 'data-pjax' => '0',
                                                ]);
                                            },

                                            'delete' => function ($url, $model) {


                                                return Html::a('<span class="icon-trash"></span>', array('banners/delete', 'id' => $model->id), array('onClick' => 'return confirm("' . Yii::t('app', 'Are you sure you want to delete?') . '")', 'title' => Yii::t('app', 'Delete')));

                                            }
                                        ],

                                    ],
                                ],
                            ]); ?>
                        </div>              </div>
                        
                    </div>
                    
                </div>
                
            </div>
        </div>
        



