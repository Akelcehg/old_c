<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 02.03.16
 * Time: 12:10
 */

use app\components\Alerts\widgets\AlertsOneTypeWidget;
use yii\bootstrap\Tabs;
use app\modules\counter\components\AlertsView;
use app\modules\counter\components\EventCalendar;
use app\modules\counter\components\IndicationsHistoryView;

echo Tabs::widget([

    'items' => [

        [
            'label' => 'История показаний',
            'content' => IndicationsHistoryView::widget(['id'=>$counter->id,]),
        ],
        [
            'label' => 'Лог предупреждений',
            'content' => AlertsOneTypeWidget::widget(['serial_number' => $counter->modem->serial_number]),
        ],
    ],

]);