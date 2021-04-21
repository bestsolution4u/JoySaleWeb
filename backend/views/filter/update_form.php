<?php
   use yii\helpers\Html;
   use yii\widgets\ActiveForm;
   use common\models\Filtervalues;
   use conquer\toastr\ToastrWidget;
   
   /* @var $this yii\web\View */
   /* @var $model common\models\Filter */
   /* @var $form yii\widgets\ActiveForm */
   $siteSettings = yii::$app->Myclass->getSitesettings();
$this->title = $siteSettings->sitename;
$this->params['breadcrumbs'][] = $this->title;
   ?>


           
              <div class="filter-form">
 <?php $form = ActiveForm::begin(['options' =>['class' => 'boxShadow p-3 bgWhite m-b20 filterform']]); ?>
 <h4 class="m-b25  blueTxtClr p-t10 p-b10"> <?php echo Yii::t('app','Update').' '.Yii::t('app','Filter'); ?></h4>
  
   <?php echo $form->field($model, 'name')->label('Label name')->textInput(['maxlength' => 18]) ?>
   <div class="help-block-name errormessage"></div>
   <?php
      echo $form->field($model, 'type')->label('Label type')->dropDownList(
                  [ 'dropdown' => 'Dropdown', 'range' => 'Range','multilevel'=> 'Multi level']
          ); ?>
   <!--<div id="gridsectioddn">
       <div style="float: right;">
            <a href="javascript:void(0);" id="add">Add</a>
            
            <a href="javascript:void(0);" id="reset">&nbsp; Reset</a>
       </div>

       <?php
            if(isset($model->value) && $model->value != '')
            {
                $explode_values = explode(',', $model->value);

                
                $i=0;
                foreach($explode_values as $key=>$parse)
                {
                    ?>
                        <div class="form-group field-filter-name required dynamic_<?php echo $i; ?>">
                            <input type="text" name="dynamic[]" placeholder="Value" id="dynamic_<?php echo $i; ?>" class="form-control field" value="<?= $parse ?>" />
                            <a href="javascript:void(0);" onclick="removeattribute('dynamic_<?php echo $i; ?>');" class="dynamic_<?php echo $i; ?>">&nbsp; Remove</a>
                       </div>
                    <?php
                    $i++;
                }
            }else{
                ?>
                    <div class="form-group field-filter-name required">
                        <input type="text" name="dynamic[]" placeholder="Value" id="dynamic_dropdown" class="form-control" value="" />
                        <div class="help-block-dropdown errormessage"></div>
                   </div>
                <?php
            }
       ?>
       <div class="inputs form-group field-filter-name required"></div>
   </div>-->

   <input type="hidden" name="filterval" id="filterval" value="<?php echo (isset($model->value) && $model->value != '') ? $model->value : 'null'; ?>" />
   <div class="form-group" id="dropdownsection">
      <!--<input type="text" name="Filter[dynamic][dropdown]" class="form-control" value="" data-role="tagsinput" />-->
   </div>
    <div class="help-block-multilevelvalues"></div>
   <div class="form-group" id="multilevelsection" style="display: none;">
    <div style="float: right;">
            <a href="javascript:void(0);" id="addlevel">Add</a>
    </div>
    <?php
      $filterValues = Filtervalues::find()->select('id')->where(['filter_id'=>$model->id,'type'=>'multilevel'])->all();
      if(empty($filterValues))
      {
        ?>

          <div class="form-group field-filter-name required">
         
                    <label class="control-label" for="filter-type">Values</label>
                      <input type="text" name="Filter[dynamic][parent][]" id="parent" placeholder="parent level" class="form-control form-control parent"  />
                      <input type="text" name="Filter[dynamic][child][]" id="child" placeholder="child level" class="form-control form-control field child" data-role="tagsinput" />
                  </div>
        <?php
      }else{
        foreach($filterValues as $filterKey=>$filterVal)
        {
           $getLevelone = Filtervalues::find()->select(['name','id'])->where(['parentid'=>$filterVal->id,'parentlevel'=>'3'])->all();
              $i = 0;
              foreach($getLevelone as $levelvals)
              {
                  $getLevelchild = Filtervalues::find()->select(['name'])->where(['parentid'=>$levelvals->id,'parentlevel'=>'4'])->all();      
                  $valarr = array();
                  foreach($getLevelchild as $sss)
                  {
                    $valarr[] = $sss->name;
                  }
                  $childwithscan = implode(',', $valarr);
                ?>
                    <div class="form-group field-filter-name required dynamic_<?php echo $i; ?>">
                      <label class="control-label" for="filter-type">Values</label>
                        <input id="parent" type="text" name="Filter[dynamic][parent][]" placeholder="parent level" class="form-control form-control parent" value="<?php echo $levelvals->name; ?>" />
                        <input id="child" type="text" name="Filter[dynamic][child][]" placeholder="child level" class="child form-control form-control field" value="<?php echo $childwithscan; ?>" data-role="tagsinput" id="child" />
                        <a href="javascript:void(0);" onclick="removeattribute('dynamic_<?php echo $i; ?>');" class="dynamic_<?php echo $i; ?>">&nbsp; Remove</a>
                    </div>
                <?php  
                $i++; 
              }

      }

      
        ?>
           
        <?php 

        ?>
          
              
          
        <?php
      }
    ?>

    <div class="inputs form-group field-filter-name required"></div>

   </div>
   <div id="rangesection" style="display: none;">
      <div class="form-group field-filter-name required">
      <?php
        if(isset($model->value) && $model->value != '' && $model->type == 'range')
          {
            $splitValues = explode(';', $model->value);
      ?>
              <input type="number" name="Filter[dynamic][min]" placeholder="Minimum" max="99999999999" id="dynamic_range_min" class="" min="0" value="<?php echo  $splitValues[0]; ?>" />
              <input type="number" min="0" name="Filter[dynamic][max]" placeholder="Maximum" id="dynamic_range_max" class="" max="99999999999" value="<?php echo $splitValues[1]; ?>" />
              <div class="help-block-range errormessage"></div>
      <?php
          }else{ ?>
              <input type="number" min="0" name="Filter[dynamic][min]" placeholder="Minimum" id="dynamic_range_min" max="99999999999" class="" value="" />
            <input type="number" min="0" name="Filter[dynamic][max]" placeholder="Maximum" id="dynamic_range_max" max="99999999999" class="" value="" />
       <?php } ?>
            
            <div class="help-block-range"></div>
      </div>
   </div>

   
   <div class="form-group">
      <?= Html::submitButton('Update', ['class' => 'btn btn-primary','id'=>'filter_update']) ?>
   </div>
   <?php ActiveForm::end(); ?>

   <style type="text/css">
  .bootstrap-tagsinput {
  box-shadow: none;
  padding: 7px 12px;
  border: 1px solid #e3e3e3;
  width:100%;
}
.bootstrap-tagsinput .label-info {
  background-color: #36404a !important;
  display: inline-block;
  padding: 8px 10px;
  font-size:14px;
  margin:5px;
}
/* Multiple */
.bootstrap-tagsinput > input {
  border: none;
}

#multilevelsection .bootstrap-tagsinput{
  margin-top: 10px;
  margin-bottom: 10px;
}
</style>

<script type="text/javascript">

  $(document).ready(function() {

$(".bootstrap-tagsinput input").tagsinput({
  cancelConfirmKeysOnEmpty: false,
  confirmKeys: [13, 44]
});

  
$('.filterform').bind('keypress', function(e) {
    if (e.keyCode == 13) {
        e.preventDefault();
    }
});


  });
    




</script>