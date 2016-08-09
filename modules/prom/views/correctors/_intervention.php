<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 24.02.16
 * Time: 14:20
 */

use app\models\EmergencySituation;
use yii\helpers\Html;

$int = \app\models\Intervention::find()
    ->where(['all_id' => $counterId])
    ->orderBy(['timestamp' => SORT_ASC])
    ->one();

if(empty($int->timestamp)){
    $value=false;
}else{
    $value=$int->timestamp;
}

echo \app\modules\prom\components\TabsDatePicker::widget(['mode'=>'day','classY'=>'yearI','classM'=>'monthI','beginDate'=>$value]);
echo \app\modules\prom\components\InterventionWidget::widget(['id'=>$counterId]);
