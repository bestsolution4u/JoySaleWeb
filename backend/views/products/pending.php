<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Products;
use common\models\Sitesettings;
use dosamigos\datepicker\DatePicker;
use conquer\toastr\ToastrWidget;
use kartik\alert\Alert;

//use kartik\date\DatePicker;
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
                <h4 class="m-b25 blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Items').' '.Yii::t('app','Management'); ?></h4>
            </div>
                    
            <div class="">
                <?php
                    $siteSettingsModel = Sitesettings::find()->orderBy(['id' => SORT_DESC])->one();
                    if($siteSettingsModel->product_autoapprove=='1'){$checked='checked';}else{$checked='';}
                ?>
                <div class=" align-self-center">
                    <h6 class="p-t10"><?php echo Yii::t('app','Auto Approval');?></h6>
                    <div class="custom-control custom-switch m-b20">
                        <input type="checkbox" class="custom-control-input autoapprove" id="Products_myoffer"  value="<?php echo $siteSettingsModel->product_autoapprove;?>" <?php echo $checked;?>>
                        <label class="custom-control-label" for="Products_myoffer"></label>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between  flex-column flex-sm-row">
            <div class="materialTab m-b25">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item m-t15">
                            <a class="nav-link " id="home-tab" href="<?php echo Yii::$app->homeUrl."products/index" ?>"><?php echo Yii::t('app','Approved Items');?></a>
                        </li>
                        <li class="nav-item selected m-t15">
                            <a class="nav-link active" id="profile-tab" href="javascript:void(0);"
                                ><?=Yii::t('app','Pending Items')?></a>
                        </li>
                    </ul>
            </div>
            <div class=" m-t5">
                <p style="color:red; padding: 10px 15px 0px; " class="pull-right"><?php echo Yii::t('app',"Use '0' in price filter for Giving Away Products");?>  </p>
            </div>
        </div>


        <div class="table-responsive"  id="users-grid">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'summary' => '<div class="summary"> '.Yii::t("app","Showing").' &nbsp<b>{begin}</b> - <b>{end}</b> &nbsp '.Yii::t("app","of").' &nbsp<b>{count}</b>&nbsp '.Yii::t("app","Users").' </div>',
                'columns' => [
                    ['attribute' => 'productId',
                    'label' => Yii::t('app','product id')],
                    ['attribute' => 'name',
                    'label' => Yii::t('app','Name')],   
                    [
                        'attribute'=>'price',
                        'label'=>Yii::t('app','Price'),
                          'format'=>'raw',    
                        'value' => function($model, $key, $index)
                        {   
                            if($model->price == 0)
                            {
                              return "Giving Away";
                            } else
                            {  
                                return $model->price;
                            }
                        },
                    ],   
                    [
                        'attribute'=>'createdDate',
                        'label'=>Yii::t('app','Created Date'),
                        'value'=>'createdDate',
                        'format'=>['DateTime','php:d-m-Y'],
                        'filter'=>DatePicker::widget([
                            'name' => 'createdDate',
                            'value' => isset($_GET['createdDate']) ? $_GET['createdDate'] : '',
                            'template' => '{addon}{input}',
                                'clientOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd-mm-yyyy'
                                ]
                        ]),
                   
                     ],          
                    [
                        'attribute'=>'approvedStatus',
                        'header'=>Yii::t('app','Status'),
                        'filter' =>false,
                        'format'=>'raw',    
                        'value' => function($model, $key, $index)
                        {   
                            if($model->approvedStatus == 1)
                            {
                            $val = Html::a(Yii::t('app','Disable'),array(''),array('class'=>'btn btn-sm btn-danger', 'data-toggle' => 'modal' , 'data-target'=>'#deactivate'.$model->productId, 'title'=>Yii::t('app','Click here to Disable product')));
                            }
                            else
                            {   
                                $val = Html::a(Yii::t('app','Enable'),array(''),array('class'=>'btn btn-sm btn-success', 'data-toggle' => 'modal' , 'data-target'=>'#activate'.$model->productId, 'title'=>Yii::t('app','Click here to Enable product')));
                            }                    
                            
                            return $val.'<div class="modal fade" id="deactivate'.$model->productId.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content w-75 mx-auto text-center">
                
                            <div class="modal-body">'.Yii::t('app','Are you sure disable this product?').'
                            </div>
                            <div class="m-t20 m-b20 text-center justify-content-center">
                             
                              <button type="button" class="btn btn-primary m-r20"><a href="'.Yii::$app->urlManager->createUrl(['products/status', 'status'=>'0', 'id'=>$model->productId]).'" style="color:#fff;">'.Yii::t('app','Okay').'</a></button>
                              <button type="button" class="btn btn-danger" data-dismiss="modal">'.Yii::t('app','Cancel').'</button>
                            </div>
                          </div>
                        </div>
                      </div><div class="modal fade" id="activate'.$model->productId.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content w-75 mx-auto text-center">
              
                          <div class="modal-body">'.Yii::t('app','Are you sure enable this product?').'
                          </div>
                          <div class="m-t20 m-b20 text-center justify-content-center">
                           
                            <button type="button" class="btn btn-primary m-r20"><a href="'.Yii::$app->urlManager->createUrl(['products/status', 'status'=>'1', 'id'=>$model->productId]).'" style="color:#fff;">'.Yii::t('app','Okay').'</a></button>
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
                    ],
                             [
                        'header'=>Yii::t('app','Delete'),
                        'format'=>'raw',    
                        'value' => function($model, $key, $index)
                        {  
                     //   $Condition = Productconditions::find()->where(['id' => $model->id])->one();    
                        //$productCount = Products::find()->where(['productCondition' => $Condition->condition])->count();
                        //if($productCount == 0) {
                            $val = Html::a('<button class="btn btn-danger align-text-top border-0"><i class="fa fa-trash"></i></button>',array(''),array('data-toggle' => 'modal' , 'data-target'=>'#delete'.$model->productId, 'title'=>Yii::t('app','Delete')));
                        

                            return $val.'<div class="modal fade" id="delete'.$model->productId.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                  <div class="modal-content w-75 mx-auto text-center">
                        
                                    <div class="modal-body">'.Yii::t('app','Are you sure you want to delete ?').'
                                    </div>
                                    <div class="m-t20 m-b20 text-center justify-content-center">
                                     
                                      <button type="button" class="btn btn-primary m-r20"><a href="'.Yii::$app->urlManager->createUrl(['products/delete', 'id'=>$model->productId]).'" style="color:#fff;">'.Yii::t('app','Okay').'</a></button>
                                      <button type="button" class="btn btn-danger" data-dismiss="modal">'.Yii::t('app','Cancel').'</button>
                                    </div>
                                  </div>
                                </div>
                              </div>';//}
                        },
                    ],

                    ]
                ]); 
            ?>
        </div>
    </div>
    