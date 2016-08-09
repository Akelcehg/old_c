<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 25.02.16
 * Time: 13:59
 */

use yii\bootstrap\Tabs;

echo Tabs::widget([


    'items' => [
        [
            'label' => Yii::t('prom',"Daily"),
            'content' =>
                \app\modules\prom\components\TabsDatePicker::widget(['mode'=>'day','classY'=>'yearD','classM'=>'monthD','beginDate'=>$cc->firstDayReportDate]).
                //\app\modules\prom\components\CorrectorDailyDataComponent::widget(['id' => $id,'year'=>date("y"),"month"=>date("n")]),
                \app\modules\prom\components\ReportChecker\widgets\CorrectorDailyDataWidget::widget(['id' => $id,'date'=>date("Y-m")]),
            'options'=>['id'=>'dailyReports']

        ],
        [
            'label' => Yii::t('prom',"Monthly"),
            'content' =>
            \app\modules\prom\components\TabsDatePicker::widget(['mode'=>'month','classY'=>'year','beginDate'=>$cc->firstMonthReportDate]).
                \app\modules\prom\components\CorrectorMonthDataComponent::widget(['id' => $id,'year'=>date("y")]),
            'options'=>['id'=>'monthReports'],

        ],
    ],


]);


