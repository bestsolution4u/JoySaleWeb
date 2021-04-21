<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset Password';
$this->params['breadcrumbs'][] = $this->title;
?>

      <?php if(Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success" role="alert" id="successMessage">
                <button id="close-error" type="button" class="error close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

      <?php if(Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger" role="alert" id="successMessage">
                <button id="close-error" type="button" class="error close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>

<div class=" card-box">
    <div class="panel-heading text-center"> 
       <img src="https://joysalescript.com/media/logo/5919_joysale_logo_gray.png" alt="" />    </div> 


    <div class="panel-body">

      <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

             
             <h5>Please choose your new password!  </h5>
         
                  <div class="form-group"> 
                <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control','placeholder'=>'Reset Password', 'required'=>'required', 'options' => ['autocomplete' => 'off']])->label(false) ?>
                  </div>
            
                
       


           <div class="form-group"> 
                    <?= Html::submitButton('Reset', ['class' => 'btn btn-pink btn-block text-uppercase waves-effect waves-light', 'name' => 'login-button']) ?>
                </div>


            <?php ActiveForm::end(); ?> 


    <?php /* $form = ActiveForm::begin(['id' => 'login-form']); ?>

<?= $form->field($model, 'username')->textInput(['autofocus' => true, 'class' => 'form-control','placeholder'=>'Email',  'options' => ['autocomplete' => 'off']])->label(false) ?>


<?= $form->field($model, 'password')->passwordInput(['class' => 'form-control','placeholder'=>'Password',  'options' => ['autocomplete' => 'off']])->label(false) ?>



<div class="form-group">
    <?= Html::submitButton('Login', ['class' => 'btn btn-pink btn-block text-uppercase waves-effect waves-light', 'name' => 'login-button']) ?>
</div>

<?php ActiveForm::end(); */ ?>
   </div>   
    </div>         