<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\UserCounters;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;
?>
<div style="width:60%;padding-left: 20px;">
    <?php $form = ActiveForm::begin(array('id' => 'form', 'action' => '/admin/counter/addindications')); ?>
    <div class="row">                               
        <?php echo $form->errorSummary($indication); ?>
        <div class="errorMessage <?php echo get_class($indication) ?>_errors_em_" style="display: none;"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($indication, 'user_counter_id')->dropDownList(ArrayHelper::map(UserCounters::find()->all(), 'id', 'id')); ?>
        <div class="errorMessage <?php echo get_class($indication) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($indication, 'indications')->textInput(); ?>
        <div class="errorMessage <?php echo get_class($indication) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($indication, 'created_at')->widget(DatePicker::className(),['dateFormat' => 'yyyy-MM-dd','value'=>date('Y-M-d')]);?>
        <div class="errorMessage <?php echo get_class($indication) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <?php echo Html::submitButton('Сохранить'); ?>
    <?php ActiveForm::end(); ?>
</div>


