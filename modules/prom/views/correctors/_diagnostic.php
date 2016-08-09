<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 24.02.16
 * Time: 14:20
 */

use app\models\EmergencySituation;
use yii\helpers\Html;

$diag = \app\models\Diagnostic::find()
    ->where(['all_id' => $counterId])
    ->orderBy(['timestamp' => SORT_ASC])
    ->one();

if(empty($diag->timestamp)){
    $value=false;
}else{
    $value=$diag->timestamp;
}

echo \app\modules\prom\components\TabsDatePicker::widget(['mode'=>'day','classY'=>'yearDiag','classM'=>'monthDiag','beginDate'=>$value]);
echo \app\modules\prom\components\DiagnosticWidget::widget(['id'=>$counterId]);
