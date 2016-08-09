<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div style="width:60%;padding-left: 20px;">
    <?php  $form =ActiveForm::begin(array('id' => 'form','action' => '/admin/counter/addcounter')); ?>
        <div class="row">                               
            <?php echo $form->errorSummary($counter); ?>
            <div class="errorMessage <?php  echo get_class($counter) ?>_errors_em_" style="display: none;"></div>
        </div>
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($counter, 'user_id')->textInput(); ?>
            <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>

        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($counter, 'serial_number')->textInput(); ?>
            <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($counter, 'initial_indications')->textInput(); ?>
            <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($counter, 'last_indications')->textInput(); ?>
            <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($counter, 'update_interval')->textInput(); ?>
            <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($counter, 'battery_level')->textInput(); ?>
            <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
       <?php echo Html::submitButton('Save'); ?>
    <?php ActiveForm::end(); ?>
</div>