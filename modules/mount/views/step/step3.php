<?php
use app\models\CounterModel;
use app\models\Address;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="container">


    <div class="col-md-12">

        <?php

        $form = ActiveForm::begin(['id' => 'form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($counter, 'geo_location_id')->dropDownList(['Выберите адресс'] + ArrayHelper::map(Address::find()->all(), 'id', 'fulladdress'), ['disabled' => 'true']); ?>
            <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>

        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($counter, 'rmodule_id')->textInput(['disabled' => 'true']) ?>
            <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>

        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($counter, 'last_indications')->textInput(['disabled' => 'true']) ?>
            <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>

        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($counter, 'type')->dropDownList(['Выберите измеряемую  среду'] + $counter->getCounterTypeList(), ['disabled' => 'true']); ?>
            <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>

        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($counter, 'counter_model')->dropDownList(['Выберите  модель модема'] + ArrayHelper::map(CounterModel::find()->all(), 'id', 'name'), ['disabled' => 'true']); ?>
            <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>

        <div class="row">
            <div class="clear"></div>
            <?php echo $form->field($counter, 'resizedImage')->fileInput(['id' => 'counterFileSelect']) ?>
            <div class="errorMessage <?php echo get_class($counter) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>


        <?php if (isset($images[0])): ?>

            <?= Html::img(\yii\helpers\BaseUrl::base() . '/' . $images[0],
                [
                    "style" => 'max-width: 100%;height: auto;',
                    'class' => 'img-responsive'
                ]
            ) ?>

        <?php endif; ?>
        <?php ActiveForm::end(); ?>

        <div style="margin-top: 5%;">
            <?php echo \yii\helpers\Html::a('Изменить данные счётчика', array('step/step1',
                'serial_number' => $counter['rmodule_id']), [
                'class' => 'btn btn-info btn-lg ',
                'style' => 'width:100%;'
            ]); ?>
        </div>
        <div style="margin-top: 5%;">
            <?php echo \yii\helpers\Html::a('Изменить показания', array('step/step2',
                'counter_id' => $counter['id']), [
                'class' => 'btn btn-info btn-lg',
                'style' => 'width:100%;'
            ]); ?>
        </div>
        <div style="margin-top: 5%;">
            <?php echo \yii\helpers\Html::a('Выбор модуля', array('/mount'), [
                'class' => 'btn btn-info btn-lg',
                'style' => 'width:100%;'
            ]); ?>
        </div>
    </div>


</div>