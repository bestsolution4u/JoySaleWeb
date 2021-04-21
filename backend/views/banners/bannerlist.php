<?php
use yii\helpers\Html;
use yii\grid\GridView;
use conquer\toastr\ToastrWidget;
use kartik\alert\Alert;
use yii\helpers\Url;
use dosamigos\datepicker\DatePicker;
$this->params['breadcrumbs'][] = $this->title;
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
                <h4 class="m-b25 blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Approved Banners')?></h4>
            </div>
                    
            <div class="">
                    <?php 
                            echo Html::a(Yii::t('app','Approved'),['banners/bannerlist'],array('class' => "btn btn-sm btn-default pull-right custom-class btn-success",'style'=>"cursor: pointer; font-size: 12px; line-height: 16px;margin-left:5px;")
                            ).'&nbsp;'; 
                            echo Html::a(Yii::t('app','Rejected'),['banners/cancelled'],array('class' => "btn btn-sm btn-default pull-right custom-class btn-primary",'style'=>"cursor: pointer; font-size: 12px; line-height: 16px;margin-left:5px; ")
                            ).'&nbsp;';
                            echo Html::a(Yii::t('app','New Banners'), ['banners/paidbanner'],array('class' => "btn btn-sm btn-default pull-right custom-class btn-primary",'style'=>"cursor: pointer; font-size: 12px; line-height: 16px; margin-left:5px;")).'&nbsp;';
                         
                            ?>
             
            </div> 
        </div>


        <div class="table-responsive"  id="users-grid">

        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => '<div class="summary"> '.Yii::t("app","Showing").' &nbsp<b>{begin}</b> - <b>{end}</b> &nbsp '.Yii::t("app","of").' &nbsp<b>{count}</b>&nbsp '.Yii::t("app","Banners").' </div>',
         'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                  
                                     [
                                        'header'=>Yii::t('app','Website'),
                                        'format' =>['image',['width'=>'75','height'=>'auto']],
                                        'value' => function($model) {     
                                            $path = Yii::$app->urlManagerfrontEnd->baseUrl.'/media/banners/';                                  
                                       return $path.$model->bannerimage;
                                     } ,
                                     
                                     ],
                                    
                                     [
                                        'header'=>Yii::t('app','Mobile'),
                                        'format' =>['image',['width'=>'75','height'=>'auto']],
                                        'value' => function($model) {     
                                            $path = Yii::$app->urlManagerfrontEnd->baseUrl.'/media/banners/';                                  
                                       return $path.$model->appbannerimage;
                                     } ,
                                     
                                     ],
                                 
                                    
                                   [
                                        'attribute' => 'startdate',
                                        'headerOptions' => ['style' => 'width:80px'],
                                        'contentOptions' => ['style' => 'width:80px; white-space: normal;','class' => 'small-input'],
                                        'filter' => DatePicker::widget([
                                            'model'=>$searchModel,
                                            'attribute'=>'startdate',
                                         'template' => '{addon}{input}',
                                            'clientOptions' => [
                                                'autoclose' => true,
                                                'format' => 'yyyy-mm-dd',
                                                 'orientation'=> 'left bottom'
                                            ]
                                                                            ]),
                                                                            'format' => 'html',
                                    ],
                                   [
                                        'attribute' => 'enddate',
                                        'headerOptions' => ['style' => 'width:80px'],
                                        'contentOptions' => ['style' => 'width:80px; white-space: normal;','class' => 'small-input'],
                                        'filter' => DatePicker::widget([
                                            'model'=>$searchModel,
                                            'attribute'=>'enddate',
                                           'template' => '{addon}{input}',
                                            'clientOptions' => [
                                                'autoclose' => true,
                                                'format' => 'yyyy-mm-dd',
                                                 'orientation'=> 'left bottom'
                                            ]
                                                                            ]),
                                                                            'format' => 'html',
                                    ],
                                    ['header'=>Yii::t('app','Posted On'),
                                   
                                    'attribute' => 'createdDate',
                                      'filter'=>false,
                                    'content' => function($model) {
                                        return date("D M j", strtotime($model->createdDate)); },
                                        'headerOptions' =>  ['class' => 'small-input'] ],
                               
                                  
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{view}',
                                        'headerOptions' => ['style' => 'width:15%;text-align:center;'],
                                        'contentOptions' => ['style' => 'text-align:center;'],
                                        'header' => Yii::t('app', 'Action'),
                                        'buttons' => [


                                            'view' => function ($url, $model) {
                                                $url = Url::to(['banners/viewbanner', 'id' => $model->id]);


                                                return Html::a('<button class="btn btn-primary align-text-top border-0"><i class="fa fa-eye"></i></button>', $url, [
                                                    'title' => Yii::t('app', Yii::t('app', 'View')),
                                                    'data-method' => 'post', 'data-pjax' => '0',
                                                ]);
                                            },

                                            // 'update' => function ($url, $model) {

                                            //     return Html::a('<span class="icon-note"></span>', $url, [
                                            //         'title' => Yii::t('app', 'Update'),
                                            //         'data-method' => 'post', 'data-pjax' => '0',
                                            //     ]);
                                            // },

                                            // 'delete' => function ($url, $model) {


                                            //     return Html::a('<span class="icon-trash"></span>', array('banners/delete', 'id' => $model->id), array('onClick' => 'return confirm("' . Yii::t('app', 'Are you sure you want to delete?') . '")', 'title' => Yii::t('app', 'Delete')));

                                            // }
                                        ],

                                    ],
                                ],
            ]); ?>
        </div>
    </div>
    
