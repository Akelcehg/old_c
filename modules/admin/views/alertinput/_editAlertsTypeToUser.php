<?php

use yii\helpers\Html;
use app\models\AlertsTypes;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin(['id' => 'form', 'options' => ['enctype' => 'multipart/form-data']]);
echo Html::checkBoxList('alertsType',ArrayHelper::map($type, 'alerts_type_id', 'alerts_type_id'), ArrayHelper::map(AlertsTypes::find()->all(), 'id', 'alertTypeText'));
echo Html::submitButton('Сохранить');
ActiveForm::end();
?>
