
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
            <?php echo $form->errorSummary($rmodule); ?>
            <div class="errorMessage <?php  echo get_class($rmodule) ?>_errors_em_" style="display: none;"></div>
        </div>
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($rmodule, 'user_id')->dropDownList([Yii::t('counter','chose_user')] + ArrayHelper::map(User::find()->all(), 'id', 'fullname'),['id'=>'select2']);?>
            <div class="errorMessage <?php echo get_class($rmodule) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>

      <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($rmodule, 'counter_id')->textInput(); ?>
            <div class="errorMessage <?php echo get_class($rmodule) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>

        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($rmodule, 'serial_number')->textInput(['readonly'=>'readonly']); ?>
            <div class="errorMessage <?php echo get_class($rmodule) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($rmodule, 'modem_id')->textInput(); ?>
            <div class="errorMessage <?php echo get_class($rmodule) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    

    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($rmodule, 'last_impulse')->textInput(['readonly'=>'readonly']); ?>
            <div class="errorMessage <?php echo get_class($rmodule) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($rmodule, 'battery_level')->textInput(['readonly'=>'readonly']); ?>
            <div class="errorMessage <?php echo get_class($rmodule) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($rmodule, 'geo_location_id')->dropDownList(['Выберите адресс'] + ArrayHelper::map(Address::find()->all(), 'id', 'fulladdresswithcity'),['id'=>'select3','value'=>$rmodule->geo_location_id]); ?>
            <div class="errorMessage <?php echo get_class($rmodule) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    
         <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($rmodule, 'timecode')->textInput(['readonly'=>'readonly']); ?>
            <div class="errorMessage <?php echo get_class($rmodule) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($rmodule, 'updated_at')->textInput(['readonly'=>'readonly']); ?>
            <div class="errorMessage <?php echo get_class($rmodule) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($rmodule, 'created_at')->textInput(['readonly'=>'readonly']); ?>
            <div class="errorMessage <?php echo get_class($rmodule) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($rmodule, 'update_interval')->textInput(); ?>
        <div class="errorMessage <?php echo get_class($rmodule) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($rmodule, 'month_update')->textInput(['readonly'=>'readonly']); ?>
        <div class="errorMessage <?php echo get_class($rmodule) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($rmodule, 'month_update_type')->textInput(['readonly'=>'readonly']); ?>
        <div class="errorMessage <?php echo get_class($rmodule) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>
    
        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($rmodule, 'is_ignore_alert')->checkbox()?>
            <div class="errorMessage <?php echo get_class($rmodule) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    
        <?php echo $form->field($rmodule, 'id')->hiddenInput()->label(false)->error(false); ?>
        <?php echo Html::submitButton(Yii::t('app','Save')); ?>
    <?php ActiveForm::end(); ?>
</div>


