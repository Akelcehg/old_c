<?php
use app\components\Alerts\widgets\AlertsOneTypeWidget;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Tabs;
use app\modules\counter\components\AlertsView;
use app\modules\counter\components\EventCalendar;
use app\modules\counter\components\IndicationsHistoryView;
use yii\helpers\Html;

?>

<div style="width:60%;padding-left: 20px;">

    <?php $form = ActiveForm::begin(array('id' => 'form')); ?>
    <div class="row">
        <?php echo $form->errorSummary($corrector); ?>
        <div class="errorMessage <?php echo get_class($corrector) ?>_errors_em_" style="display: none;"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?= $form->field($corrector, 'hw_status')->dropDownList(\app\models\CorrectorToCounter::getAllStatuses())->label(Yii::t('prom','Status')) ?>
        <div class="errorMessage <?php echo get_class($corrector) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <?php echo $form->field($corrector, 'id')->hiddenInput()->label(false)->error(false); ?>

    <?php echo Html::submitButton('Сохранить'); ?>


    <?php ActiveForm::end(); ?>

</div>