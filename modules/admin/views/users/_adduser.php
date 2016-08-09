
<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/*$this->pageTitle = $pageTitle;
$this->breadcrumbs = array(
    'Administration' => array('administration/index'),
    $pageTitle
);*/
?>



<div style="width:60%;padding-left: 20px;">
    <?php  $form =ActiveForm::begin(array('id' => 'form','action' => '/admin/users/adduser')); ?>
        <div class="row">                               
            <?php echo $form->errorSummary($user); ?>
            <div class="errorMessage <?php  echo get_class($user) ?>_errors_em_" style="display: none;"></div>
        </div>
        <div style="font-size:16px; margin-bottom: 5px;"><b>Please enter a new password</b></div>
        <div class="row">
            <div class="clear"></div>

            <?php echo $form->field($user, 'email')->textInput(); ?>
            <?php //echo $form->error($model, 'email'); ?>
            <div class="errorMessage <?php echo get_class($user) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($user, 'password')->passwordInput(); ?>
            <?php //echo $form->error($model, 'email'); ?>
            <div class="errorMessage <?php echo get_class($user) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($user, 'password_repeat')->passwordInput(); ?>
            <?php //echo $form->error($model, 'password'); ?>
            <div class="errorMessage <?php echo get_class($user) ?>_password_repeat_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>

        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($user, 'first_name')->textInput(); ?>
            <div class="errorMessage <?php echo get_class($user) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
        
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($user, 'last_name')->textInput(); ?>
            <div class="errorMessage <?php echo get_class($user) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
        
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($user, 'nick_name')->textInput(); ?>
            <div class="errorMessage <?php echo get_class($user) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
       <?php echo Html::submitButton('Save'); ?>
    <?php ActiveForm::end(); ?>
</div>


