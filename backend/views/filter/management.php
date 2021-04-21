<?php

use yii\helpers\Html;
use yii\grid\GridView;
use conquer\toastr\ToastrWidget;
use common\models\Productfilters;
$siteSettings = yii::$app->Myclass->getSitesettings();
$this->title = $siteSettings->sitename;
$this->params['breadcrumbs'][] = $this->title;


?>
 <div class="boxShadow p-t20 p-b20 p-l15 p-r10 bgWhite m-b20 userinfo">
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
                <h4 class="m-b25 blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','  Filters ').' '.Yii::t('app','Management')?></h4>
            </div>
                    
            <div class="">
                <button class='btn btn-primary align-text-top border-0 m-b10'>
                    <?=Html::a('<i class="fa fa-plus p-r10"></i> '.Yii::t('app','Add Filter'),['filter/addfilter']); ?> 
                </button> 
            </div>
        </div>

<div class="table-responsive">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'type',
           // 'value:ntext',
            //'isRequired',
            //'status',

               ['class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'header'=>Yii::t('app', 'View'),
                    'buttons' => [

                        'view' => function ($url, $model) {
                    
                            return Html::a('<button class="btn btn-primary align-text-top border-0"><i class="fa fa-eye"></i></button>', $url, [
                                'title' => Yii::t('app', 'View'),
                                'data-method' => 'post', 'data-pjax' => '0',
                            ]);
                        }
                    ],
                    ],


       ['class' => 'yii\grid\ActionColumn',
                    'template' => '{update}',
                    'header'=>Yii::t('app', 'Edit'),
                    'buttons' => [

                        'update' => function ($url, $model) {
                    
                            return Html::a('<button class="btn btn-success align-text-top border-0"><i class="fa fa-edit"></i></button>', $url, [
                                'title' => Yii::t('app', 'Edit'),
                                'data-method' => 'post', 'data-pjax' => '0',
                            ]);
                        }
                    ],
                    ],
                            
            

            ['class' => 'yii\grid\ActionColumn',
             'template' => '{delete}',
                    'header'=>Yii::t('app', 'Delete'),
            'buttons'=>[
                    
                    'delete'=>function ($url, $model) {
                        $getProductfilter = Productfilters::find()->where(['filter_id'=>$model->id])->count();
                        if($getProductfilter == 0)
                            return Html::a('<button class="btn btn-danger align-text-top border-0"><i class="fa fa-trash"></i></button>', $url, [
                            'title' => Yii::t('app', 'lead-delete'),
                        ]);
                    },
                ],

            ],
        ],
    ]); ?>


</div>
                </div>
            
