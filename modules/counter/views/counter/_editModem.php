
<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Address;
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
            <?php echo $form->errorSummary($modem); ?>
            <div class="errorMessage <?php  echo get_class($modem) ?>_errors_em_" style="display: none;"></div>
        </div>
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($modem, 'user_id')->dropDownList(['Выберите пользователя'] + ArrayHelper::map(User::find()->all(), 'id', 'fullname'),['id'=>'select2']);?>
            <div class="errorMessage <?php echo get_class($modem) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>

        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($modem, 'serial_number')->textInput(['readonly'=>'readonly']); ?>
            <div class="errorMessage <?php echo get_class($modem) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($modem, 'phone_number')->textInput(); ?>
            <div class="errorMessage <?php echo get_class($modem) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($modem, 'last_invoice_request')->textInput(['readonly'=>'readonly']); ?>
            <div class="errorMessage <?php echo get_class($modem) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($modem, 'invoice_request')->textInput(['readonly'=>'readonly']); ?>
            <div class="errorMessage <?php echo get_class($modem) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($modem, 'signal_level')->textInput(['readonly'=>'readonly']); ?>
            <div class="errorMessage <?php echo get_class($modem) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($modem, 'geo_location_id')->dropDownList(['Выберите адресс'] + ArrayHelper::map(Address::find()->all(), 'id', 'fulladdresswithcity'),['id'=>'select3','value'=>$modem->geo_location_id]); ?>
            <div class="errorMessage <?php echo get_class($modem) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    
         <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($modem, 'type')->dropDownList($modem->getModemTypeList()); ?>
            <div class="errorMessage <?php echo get_class($modem) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($modem, 'updated_at')->textInput(['readonly'=>'readonly']); ?>
            <div class="errorMessage <?php echo get_class($modem) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($modem, 'created_at')->textInput(['readonly'=>'readonly']); ?>
            <div class="errorMessage <?php echo get_class($modem) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($modem, 'everyday_update_interval')->dropDownList(
             array_map(function($element) {
                return $element.':00';
            }, range(0,23)), [
                'prompt' => 'Выключено',
            ]); ?>
            <div class="errorMessage <?php echo get_class($modem) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    
        <?php echo $form->field($modem, 'id')->hiddenInput()->label(false)->error(false); ?>
        <?php echo Html::submitButton('Сохранить'); ?>
    <?php ActiveForm::end(); ?>
</div>


