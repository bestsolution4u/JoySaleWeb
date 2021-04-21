<?php
use dosamigos\chartjs\ChartJs;
use yii\web\JsExpression;
use conquer\toastr\ToastrWidget;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\alert\Alert;
use yii\grid\GridView;

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
                <h4 class="m-b25 blueTxtClr p-t10 p-b10"><?php echo Yii::t('app','Notification Management')?></h4>
            </div>
                    
         
        </div>


        

                <?php
                $siteSettings = yii::$app->Myclass->getSitesettings();
                if(isset($siteSettings->androidkey) && $siteSettings->androidkey != "")
                    $androidkey = $siteSettings->androidkey;
                else
                    $androidkey = "";
                ?>
             <div class="form-group ">
             <label><?php echo Yii::t('app','API Key for Push notification') ?> </label>
               <input type="text" value="<?php echo $androidkey;?>" class="form-control m-b20" id="androidkey" name="androidkey">
               
                        
                        <div id="androidkeysuccess" class="errorMessage"></div>
              
                <input type="button" value="<?php echo Yii::t('app','Save'); ?>" id="androidkeysave" class="btn btn-primary align-text-top border-0 m-b10" onClick="save_android_key()">
                </div>

                    <div class="form-group">
                    <label><?php echo Yii::t('app','Send Pushnotification '); ?> </label>
                      <textarea class="admin-textarea form-control m-b20" name="admin-textarea" rows="5" cols="30"
                    id="contact-textarea"></textarea>
                           
                         
                                       <div class="option-error adminpushnot-error"></div>
                        <div class="option-success adminpushnot-success"></div>
                        <div class="contact-buttons-area">
                        <div class="btn btn-primary align-text-top border-0 m-b10" id="adminpushnot">
                        <?php echo Yii::t('app','Send'); ?>
                        </div>
                    </div>
                     </div>


<div class="row">

<div class="col-12">

    <div class="portlet"><!-- /primary heading -->
        <div class="portlet-heading">

            <h4 class="portlet-title text-dark text-uppercase">
                <?=yii::t('app','Message Logs')?>
            </h3>
           
            <div class="clearfix"></div>
        </div>
        <div id="portlet2" class="panel-collapse collapse show">
            <div class="portlet-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Message</th>
                            <th  style="width:15%;"><?=yii::t('app','Created Date')?></th>
                            <th style="width:5%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $i=0;  foreach($model as $models) { $i++; ?>
                        <tr>
                            <td><?=$i?></td>
                            <td class="message-break-word"><?=$models['message']?></td>
                            <td><?=date('M j,Y', $models['createddate']);?></td>                       
                            <td style="text-align:center;" ><?= Html::a('<button class="btn btn-danger align-text-top border-0"><i class="fa fa-trash"></i></button>',array('admin/delete', 'id'=>$models->id),array('data-toggle' => 'modal' , 'data-target'=>'#delete'.$models->id, 'title'=>Yii::t('app','Delete')));?></td>
                         <?php echo '<div class="modal fade" id="delete'.$models->id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                          <div class="modal-content w-75 mx-auto text-center">
                                
                                            <div class="modal-body">'.Yii::t('app','Are you sure you want to delete ?').'
                                            </div>
                                            <div class="m-t20 m-b20 text-center justify-content-center">
                                             
                                              <button type="button" class="btn btn-primary m-r20"><a href="'.Yii::$app->urlManager->createUrl(['admin/delete', 'id'=>$models->id]).'" style="color:#fff;">'.Yii::t('app','Okay').'</a></button>
                                              <button type="button" class="btn btn-danger" data-dismiss="modal">'.Yii::t('app','Cancel').'</button>
                                            </div>
                                          </div>
                                        </div>
                                      </div>';?>


                        </tr>
                        <?php } ?>

                        </tbody>
                    </table>

                    <?php
                    echo LinkPager::widget([
                        'pagination' => $pages,
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div> <!-- end col -->

</div>
                   


 
       
    </div>
    
