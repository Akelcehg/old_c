<?php

use app\modules\counter\components\ForcedPaymentButton;
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

    <?php $form = ActiveForm::begin(array('id' => 'form')); ?>
    <div class="row">
        <?php echo $form->errorSummary($card); ?>
        <div class="errorMessage <?php echo get_class($card) ?>_errors_em_" style="display: none;"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?= $form->field($card, 'id')->textInput() ?>
        <div class="errorMessage <?php echo get_class($card) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?= $form->field($card, 'contract_day')->textInput() ?>
        <div class="errorMessage <?php echo get_class($card) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <div class="form-group ">
            <label class="control-label">Баланс</label>
            <?= isset($modem->balance)?$modem->balance:"-" ?>
            <p class="help-block help-block-error"></p>
        </div>

        <div class="errorMessage <?php echo get_class($card) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?= $form->field($card, 'tarif')->textInput() ?>
        <div class="errorMessage <?php echo get_class($card) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?= $form->field($card, 'packet')->textarea() ?>
        <div class="errorMessage <?php echo get_class($card) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?= $form->field($card, 'request_forced_payment')->textInput() ?>
        <div class="errorMessage <?php echo get_class($card) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>


   <div class="row">
        <div class="clear"></div>
        <?= $form->field($card, 'request_get_packet')->textInput() ?>
        <div class="errorMessage <?php echo get_class($card) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?= $form->field($card, 'request_balance')->textInput() ?>
        <div class="errorMessage <?php echo get_class($card) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>


    <?php echo $form->field($card, 'id')->hiddenInput()->label(false)->error(false); ?>

    <?php echo Html::submitButton('Сохранить'); ?>

    Последний выход на связь <?= isset($modem->time_on_line)?$modem->time_on_line:"-" ?>
    <?php ActiveForm::end(); ?>

</div>


