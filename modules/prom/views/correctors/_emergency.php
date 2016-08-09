<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 24.02.16
 * Time: 14:20
 */

use app\models\EmergencySituation;
use yii\helpers\Html;

$emsit = EmergencySituation::find()
    ->where(['all_id' => $counterId])
    ->orderBy(['timestamp' => SORT_ASC])
    ->one();

if(empty($emsit ->timestamp)){
    $value=false;
}else{
    $value=$emsit ->timestamp;
}

echo \app\modules\prom\components\TabsDatePicker::widget(['mode'=>'day','classY'=>'yearD','classM'=>'monthE','beginDate'=>$value]);
echo \app\modules\prom\components\EmergencySituationWidget::widget(['id'=>$counterId]);
