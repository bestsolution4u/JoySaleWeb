<?php

use yii\helpers\Html;
use yii\grid\GridView;
use conquer\toastr\ToastrWidget;
use kartik\alert\Alert;

$this->params['breadcrumbs'][] = $this->title;
?>
 <div class="boxShadow p-t20 p-b20 p-l15 p-r10 bgWhite m-b20">
 <?php 
            if(Yii::$app->session->hasFlash('success')): 
                echo Alert::widget([
                'type' => Alert::TYPE_SUCCESS,
                'body' => Yii::$app->session->getFlash('success'),
                'delay' => 10000
                ]); 
            endif; 

            if(Yii::$app->session->hasFlash('error')): 
                echo Alert::widget([
                'type' => Alert::TYPE_DANGER,
                'body' => Yii::$app->session->getFlash('error'),
                'delay' => 1000
                ]); 
            endif;  
        ?>
       <div class="d-flex justify-content-between  flex-column flex-sm-row">
            <div>
                <h4 class="m-b25 blueTxtClr p-t10 p-b10"><?=Yii::t('app','Users').' '.Yii::t('app','Management')?></h4>
            </div>
                    
            <div class="">
                <button class='btn btn-primary align-text-top border-0 m-b10'>
                    <?=Html::a('<i class="fa fa-plus p-r10"></i> '.Yii::t('app','Add').' '.Yii::t('app','User'),['users/create']); ?> 
                </button> 
            </div>
        </div>

        <div class="table-responsive"  id="users-grid">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'summary' => '<div class="summary"> '.Yii::t("app","Showing").' &nbsp<b>{begin}</b> - <b>{end}</b> &nbsp '.Yii::t("app","of").' &nbsp<b>{count}</b>&nbsp '.Yii::t("app","Users").' </div>',
                'columns' => [
                    ['attribute' => 'userId',
                    'label' => Yii::t('app','User').' '.Yii::t('app','Id')],
                    ['attribute' => 'username',
                    'label' => Yii::t('app','Username')],
                    ['attribute' => 'name',
                    'label' => Yii::t('app','Name')],
                    ['attribute' => 'email',
                    'label' => Yii::t('app','Email')],               
                    [
                        'attribute'=>'userstatus',
                        'header'=>Yii::t('app','Manage'),
                        'filter' =>false,
                        'format'=>'raw',    
                        'value' => function($model, $key, $index)
                        {   
                            if($model->activationStatus == 1 && $model->userstatus == 1 )
                            {
                            $val = Html::a(Yii::t('app','Disable'),array(''),array('class'=>'btn btn-sm btn-danger', 'data-toggle' => 'modal' , 'data-target'=>'#deactivate'.$model->userId, 'title'=>Yii::t('app','Click here to Deactivate User')));
                            }
                            else if($model->activationStatus == 1 && $model->userstatus == 0 )
                            {   
                                $val = Html::a(Yii::t('app','Enable'),array(''),array('class'=>'btn btn-sm btn-success', 'data-toggle' => 'modal' , 'data-target'=>'#activate'.$model->userId, 'title'=>Yii::t('app','Click here to Activate User')));
                            }                    
                            else if($model->activationStatus == 0 && $model->userstatus == 0 )
                            {   
                                $val = Html::a(Yii::t('app','Resend'),array(''),array('class'=>'btn btn-sm btn-info', 'data-toggle' => 'modal' , 'data-target'=>'#resend'.$model->userId, 'title'=>Yii::t('app','Click here to resent email verification mail')));
                            }
                            else if($model->activationStatus == 0 && $model->userstatus == 1 )
                            {   
                                $val = Html::a(Yii::t('app','Resend'),array(''),array('class'=>'btn btn-sm btn-info', 'data-toggle' => 'modal' , 'data-target'=>'#resend'.$model->userId, 'title'=>Yii::t('app','Click here to resent email verification mail')));
                            }

                            return $val.'<div class="modal fade" id="deactivate'.$model->userId.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content w-75 mx-auto text-center">
                
                            <div class="modal-body">'.Yii::t('app','Are you sure deactivate this user?').'
                            </div>
                            <div class="m-t20 m-b20 text-center justify-content-center">
                             
                              <button type="button" class="btn btn-primary m-r20"><a href="'.Yii::$app->urlManager->createUrl(['users/status', 'status'=>'inactive', 'id'=>$model->userId]).'" style="color:#fff;">'.Yii::t('app','Okay').'</a></button>
                              <button type="button" class="btn btn-danger" data-dismiss="modal">'.Yii::t('app','Cancel').'</button>
                            </div>
                          </div>
                        </div>
                      </div><div class="modal fade" id="activate'.$model->userId.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content w-75 mx-auto text-center">
              
                          <div class="modal-body">'.Yii::t('app','Are you sure activate this user?').'
                          </div>
                          <div class="m-t20 m-b20 text-center justify-content-center">
                           
                            <button type="button" class="btn btn-primary m-r20"><a href="'.Yii::$app->urlManager->createUrl(['users/status', 'status'=>'active', 'id'=>$model->userId]).'" style="color:#fff;">'.Yii::t('app','Okay').'</a></button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">'.Yii::t('app','Cancel').'</button>
                          </div>
                        </div>
                      </div>
                    </div><div class="modal fade" id="resend'.$model->userId.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content w-75 mx-auto text-center">
            
                        <div class="modal-body">'.Yii::t('app','Are you sure you want to resend the verification mail?').'
                        </div>
                        <div class="m-t20 m-b20 text-center justify-content-center">
                         
                          <button type="button" class="btn btn-primary m-r20"><a href="'.Yii::$app->urlManager->createUrl(['users/resend', 'status'=>'resend', 'id'=>$model->userId]).'" style="color:#fff;">'.Yii::t('app','Okay').'</a></button>
                          <button type="button" class="btn btn-danger" data-dismiss="modal">'.Yii::t('app','Cancel').'</button>
                        </div>
                      </div>
                    </div>
                  </div>';
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
                    ]
                    ]
                ]); 
            ?>
        </div>
    </div>
    
