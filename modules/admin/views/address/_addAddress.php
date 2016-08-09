<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Regions;

?>

<?php echo \app\components\CounterMap::widget(['type' => 'addaddress']); ?>

<div style="width:60%;padding-left: 20px;">
    <?php $form = ActiveForm::begin(array('id' => 'form','enableAjaxValidation' => false,
        'enableClientValidation' => false,)); ?>
    <div class="row">
        <?php echo $form->errorSummary($address); ?>
        <div class="errorMessage <?php echo get_class($address) ?>_errors_em_" style="display: none;"></div>
    </div>


    <?php if (\app\models\User::is('admin')) { ?>
        <div class="row">
            <div class="clear"></div>
            <?php
            echo Html::label(Yii::t('address','Region'));
            echo Html::dropDownList('region', '', [Yii::t('counter','Chose region')] + ArrayHelper::map(Regions::find()->where('parent_id=0')->all(), 'id', 'name'), [

                'id' => 'region',
                'class' => "form-control"
            ]);
            echo Html::label('');
            ?>
            <div class="errorMessage <?php echo get_class($address) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>

        <div class="row">
            <div class="clear"></div>
            <?php
            echo $form->field($address, 'region_id')
                ->dropDownList([Yii::t('counter','Chose region')] + ArrayHelper::map(Regions::find()->where('parent_id!=0')->all(), 'id', 'name'), [
                    'id' => 'city',
                    'value'=>Yii::$app->request->post('Address[region_id]',0)
                ]); ?>
            <div class="errorMessage <?php echo get_class($address) ?>_password_em_" style="display: none;"></div>
            <div class="clear"></div>
        </div>
    <?php } ?>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($address, 'street')->textInput(['id' => 'street']); ?>
        <div class="errorMessage <?php echo get_class($address) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($address, 'house')->textInput(['id' => 'house']); ?>
        <div class="errorMessage <?php echo get_class($address) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($address, 'longitude')->textInput(['id' => 'long']); ?>
        <div class="errorMessage <?php echo get_class($address) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($address, 'latitude')->textInput(['id' => 'lat']); ?>
        <div class="errorMessage <?php echo get_class($address) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <div class="row">
        <div class="clear"></div>
        <?php echo $form->field($address, 'status')->dropDownList($address->getAllStatuses()); ?>
        <div class="errorMessage <?php echo get_class($address) ?>_password_em_" style="display: none;"></div>
        <div class="clear"></div>
    </div>

    <?php echo Html::submitButton('Сохранить'); ?>
    <?php ActiveForm::end(); ?>
</div>


