<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Address;
use app\models\CounterModel;
use app\models\User;

$this->registerJs(' $("#select2").select2();$("#select3").select2();');

?>


<div style="width:60%;padding-left: 20px;">
    <?php $form = ActiveForm::begin(['id' => 'form', 'options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row">
        <?php echo $form->errorSummary($cc); ?>
        <div class="errorMessage <?php echo get_class($cc) ?>_errors_em_" style="display: none;"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($cc, 'contract')->textInput(); ?>
        <div class="errorMessage <?php echo get_class($cc) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>


    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($cc, 'company')->textInput(); ?>
        <div class="errorMessage <?php echo get_class($cc) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($cc, 'geo_location_id')->dropDownList(['Выберите адресс'] + ArrayHelper::map(Address::find()->all(), 'id', 'fulladdress') ); ?>
        <div class="errorMessage <?php echo get_class($cc) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <?php echo $form->field($cc, 'id')->hiddenInput()->label(false)->error(false); ?>
    <?php echo Html::submitButton('Сохранить'); ?>
    <?php ActiveForm::end(); ?>
</div>


