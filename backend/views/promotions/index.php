<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use conquer\toastr\ToastrWidget;
use kartik\alert\Alert
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
                <h4 class="m-b25 blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Promotion Management')?></h4>
            </div>
                    
            <div class="">
                <button class='btn btn-primary align-text-top border-0 m-b10'>
                    <?=Html::a('<i class="fa fa-plus p-r10"></i> '.Yii::t('app','Add').' '.Yii::t('app','Promotions'),['promotions/create']); ?> 
                </button> 
                <button class='btn btn-primary align-text-top border-0 m-b10'>
                    <?=Html::a('<i class="fa fa-plus p-r10"></i> '.Yii::t('app','Urgent Promotions'),['promotions/urgentpromotion']); ?> 
                </button> 
            </div> 
        </div>

        <div class="table-responsive"  id="users-grid">
        <?php $currency = yii::$app->Myclass->getDbCurrencyList(); ?>
		<?php

		 if(!empty($selectedcurrency)) { ?>
				<?php $selected  = $selectedcurrency; ?>
				<?php }else {
				$selected  = '';?>
				<?php }  ?> 
				<div class="selectedpromotion m-b20 d-flex">
                <div class="m-r50 m-t5">
				<label for="Promotions_currency ">
				<?php echo Yii::t('app','Promotion').' '.Yii::t('app','Currency');?>
				</label></div>
				<select class="form-control m-b20" id="selectedoption" style="width:auto;" name="promotion" onchange="selectpromotion();">
				<?php 
							echo ' <option value="0">Select currency</option>';?>
				<?php foreach($currency as $key => $currency)
					{
					
						if($selected == $currency)
						{
					echo '<option value="'.$key.' - '.$currency.'" selected>'.$key.' - '.$currency.'</option>';
				}
						
						else {
							echo '<option value="'.$key.' - '.$currency.'">'.$key.' - '.$currency.'</option>';
						}
				 }?>
				</select>  
				</div> 
				
				<div id="loading_img" class="promotionloader" style="display:none;text-align:center;float:left;">
								<img src="<?php echo Yii::$app->urlManager->createAbsoluteUrl('images/loader.gif'); ?>" alt="Loading..." style="height: 20px; width: 20px; margin: 7px;">
								</div>
				<div class="promotion-error text-danger m-b10"></div>
				<div class="promotion-success text-success m-b10"></div>
        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => '<div class="summary"> '.Yii::t("app","Showing").' &nbsp<b>{begin}</b> - <b>{end}</b> &nbsp '.Yii::t("app","of").' &nbsp<b>{count}</b>&nbsp '.Yii::t("app","Promotions").' </div>',
        'columns' => [
            [
                'attribute' => 'name',
               'label'=>Yii::t('app','Name'),
              ],
           [
                'attribute' => 'days',
               'label'=>Yii::t('app','Days'),
              ],
            [
                'attribute' => 'price',
               'label'=>Yii::t('app','Price'),
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
                                             
                                              <button type="button" class="btn btn-primary m-r20"><a href="'.Yii::$app->urlManager->createUrl(['promotions/delete', 'id'=>$model->id]).'" style="color:#fff;">'.Yii::t('app','Okay').'</a></button>
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
    
