
<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\CounterAddress;
use app\models\User;

/*$this->pageTitle = $pageTitle;
$this->breadcrumbs = array(
    'Administration' => array('administration/index'),
    $pageTitle
);*/
$this->registerJs(' $("#select2").select2();$("#select3").select2();');

?>



<div style="width:60%;padding-left: 20px;">
    
    <?php  $form =ActiveForm::begin(array('id' => 'form')); ?>
    
        <div class="row">                               
            <?php echo $form->errorSummary($alerts); ?>
            
        </div>
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($alerts, 'serial_number')->textInput(['readonly'=>'readonly']); ?>
           
            <div class="clear"></div>
        </div>
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($alerts, 'type')->textInput(['readonly'=>'readonly']); ?>
           
            <div class="clear"></div>
        </div>
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($alerts, 'device_type')->textInput(['readonly'=>'readonly']); ?>
           
            <div class="clear"></div>
        </div>
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($alerts, 'created_at')->textInput(['readonly'=>'readonly']); ?>
            
            <div class="clear"></div>
        </div>
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($alerts, 'status')->dropDownList($alerts->getAllStatuses()); ?>
           
            <div class="clear"></div>
        </div>
    
        <?php echo $form->field($alerts, 'id')->hiddenInput()->label(false)->error(false); ?>
    
        <?php echo Html::submitButton('Сохранить'); ?>
    
    <?php ActiveForm::end(); ?>
</div>


