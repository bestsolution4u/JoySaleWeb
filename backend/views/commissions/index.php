<?php
use yii\helpers\Html;
use yii\grid\GridView;
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
                <h4 class="m-b25 blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Commission Management')?></h4>
            </div>
                    
            <div class="">
                <button class='btn btn-primary align-text-top border-0 m-b10'>
                    <?=Html::a('<i class="fa fa-plus p-r10"></i> '.Yii::t('app','Add').' '.Yii::t('app','Commission'),['commissions/create']); ?> 
                </button> 
            </div> 
        </div>

        <div class="d-flex justify-content-between  flex-column flex-sm-row">
            <div>
            </div>
                    
            <div class="">
            <?php if($commissionSetting == 1) {
                        echo Html::a('<button class="btn btn-success align-text-top border-0 m-b10"><span style="cursor:pointer;font-size:15px;line-height:18px;"> '.Yii::t('app','Enabled').' </span></button>',['commissions/status']);
                    } else { ?>
                    <?php echo Html::a('<button class="btn btn-danger align-text-top border-0 m-b10"><span style="cursor:pointer;font-size:15px;line-height:18px;"> '.Yii::t('app','Disabled').' </span></button>',['commissions/status']);

                    }?>
            </div> 
        </div>

        <div class="table-responsive"  id="users-grid">
        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '<div class="summary"> '.Yii::t("app","Showing").' &nbsp<b>{begin}</b> - <b>{end}</b> &nbsp '.Yii::t("app","of").' &nbsp<b>{count}</b>&nbsp '.Yii::t("app","Commissions").' </div>',
        'columns' => [
            [
                'attribute' => 'id',
               'header'=>Yii::t('app','Id'),
              ],
               [
                'attribute' => 'percentage',
               'header'=>Yii::t('app','PERCENTAGE'),
              ],
               [
                'attribute' => 'minRate',
               'header'=>Yii::t('app','MINIMUM RATE'),
              ],
               [
                'attribute' => 'maxRate',
               'header'=>Yii::t('app','MAXIMUM RATE'),
              ],
              [
                'attribute'=>'date',
                 'header'=>Yii::t('app','DATE'),
                'value'=>'date',
                'format'=>['DateTime','php:d-m-Y'],
                ],

                [
                    'attribute'=>'status',
                    'header'=>'STATUS',
                    'contentOptions' => ['style'=>'text-align:center;'],
                    'filter' =>false,
                    'format'=>'raw',    
                    'value' => function($model, $key, $index)
                    {   
                        if($model->status == 1)
                        {
                            $icon='<i class="fa fa-check-circle" style="color:#28a745; font-size:20px;"></i>';
                          return Html::a($icon,array(''),array('title'=>Yii::t('app','Click here to de-Activate'), 'data-toggle' => 'modal' , 'data-target'=>'#deactivate'.$model->id)).'<div class="modal fade" id="deactivate'.$model->id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content w-75 mx-auto text-center">
                  
                              <div class="modal-body">'.Yii::t('app','Are you sure deactivate this commission?').'
                              </div>
                              <div class="m-t20 m-b20 text-center justify-content-center">
                               
                                <button type="button" class="btn btn-primary m-r20"><a href="'.Yii::$app->urlManager->createUrl(['commissions/changestatus', 'id'=>$model->id]).'" style="color:#fff;">'.Yii::t('app','Okay').'</a></button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">'.Yii::t('app','Cancel').'</button>
                              </div>
                            </div>
                          </div>
                        </div>';
                        }
                        else
                        {   
                            $icon='<i class="fa fa-times-circle"  style="color:red; font-size:20px;"></i>';
                            return Html::a($icon,array(''),array('title'=>Yii::t('app','Click here to Activate'), 'data-toggle' => 'modal' , 'data-target'=>'#activate'.$model->id)).'<div class="modal fade" id="activate'.$model->id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content w-75 mx-auto text-center">
                    
                                <div class="modal-body">'.Yii::t('app','Are you sure activate this commission?').'
                                </div>
                                <div class="m-t20 m-b20 text-center justify-content-center">
                                 
                                  <button type="button" class="btn btn-primary m-r20"><a href="'.Yii::$app->urlManager->createUrl(['commissions/changestatus', 'id'=>$model->id]).'" style="color:#fff;">'.Yii::t('app','Okay').'</a></button>
                                  <button type="button" class="btn btn-danger" data-dismiss="modal">'.Yii::t('app','Cancel').'</button>
                                </div>
                              </div>
                            </div>
                          </div>';
                        }
                    },
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
                            
                    [
                        'header'=>Yii::t('app','Delete'),
                        'format'=>'raw',    
                        'value' => function($model, $key, $index)
                        {  
                            $val = Html::a('<button class="btn btn-danger align-text-top border-0"><i class="fa fa-trash"></i></button>',array(''),array('data-toggle' => 'modal' , 'data-target'=>'#delete'.$model->id, 'title'=>Yii::t('app','Delete')));
        
                            return $val.'<div class="modal fade" id="delete'.$model->id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                          <div class="modal-content w-75 mx-auto text-center">
                                
                                            <div class="modal-body">'.Yii::t('app','Are you sure you want to delete ?').'
                                            </div>
                                            <div class="m-t20 m-b20 text-center justify-content-center">
                                             
                                              <button type="button" class="btn btn-primary m-r20"><a href="'.Yii::$app->urlManager->createUrl(['commissions/delete', 'id'=>$model->id]).'" style="color:#fff;">'.Yii::t('app','Okay').'</a></button>
                                              <button type="button" class="btn btn-danger" data-dismiss="modal">'.Yii::t('app','Cancel').'</button>
                                            </div>
                                          </div>
                                        </div>
                                      </div>';
                        },
                    ],
                ]
            ]); ?>
        </div>
    </div>
    
