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
                <h4 class="m-b25 blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Banners Management')?></h4>
            </div>
                    
            <div class="">
                <button class='btn btn-primary align-text-top border-0 m-b10'>
                    <?=Html::a('<i class="fa fa-plus p-r10"></i> '.Yii::t('app','Add').' '.Yii::t('app','Banner'),['banners/create']); ?> 
                </button> 
             
            </div> 
        </div>

        <div class="d-flex flex-column flex-sm-row m-b40">
            <div class="">
                <div class=" align-self-center m-r50">
                    <h6 class="p-t10"><?php echo Yii::t('app','Enable Web Banner');?></h6>
                    <?php if($sitesettings->bannerstatus=="1"){$checked='checked';$value=1;}else{$checked='';$value=0;} ?>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input bannerapprove" id="Sitesettings_bannerstatus" name="Sitesettings[bannerstatus]"  value="<?php echo $sitesettings->bannerstatus;?>" <?php echo $checked;?>>
                        <label class="custom-control-label" for="Sitesettings_bannerstatus"></label>
                    </div>
                </div>
            </div>
          
            <div class="">
                <div class=" align-self-center">
                    <h6 class="p-t10"><?php echo Yii::t('app','Enable App Banner');?></h6>
                    <?php if($sitesettings->appbannerStatus=="1"){$checked='checked';$value=1;}else{$checked='';$value=0;} ?>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input mobilebannerapprove" id="Sitesettings_appbannerStatus" name="appbannerStatus"  value="<?php echo $sitesettings->appbannerStatus;?>" <?php echo $checked;?>>
                        <label class="custom-control-label" for="Sitesettings_appbannerStatus"></label>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive"  id="users-grid">

        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => '<div class="summary"> '.Yii::t("app","Showing").' &nbsp<b>{begin}</b> - <b>{end}</b> &nbsp '.Yii::t("app","of").' &nbsp<b>{count}</b>&nbsp '.Yii::t("app","Banners").' </div>',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


                                    'bannerimage',
                                    'appbannerimage',
                                    'bannerurl:ntext',
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
                                             
                                              <button type="button" class="btn btn-primary m-r20"><a href="'.Yii::$app->urlManager->createUrl(['banners/delete', 'id'=>$model->id]).'" style="color:#fff;">'.Yii::t('app','Okay').'</a></button>
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
    
