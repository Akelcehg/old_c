<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 02.03.16
 * Time: 12:10
 */

use app\components\Alerts\widgets\AlertsOneTypeWidget;
use app\modules\metrix\components\MetrixAlertsOneTypeWidget;
use app\modules\metrix\components\MetrixIndicationsHistoryView;
use yii\bootstrap\Tabs;
use app\modules\counter\components\AlertsView;
use app\modules\counter\components\EventCalendar;
use app\modules\counter\components\IndicationsHistoryView;

echo Tabs::widget([

    'items' => [

        [
            'label' => Yii::t('metrix','history_indiations'),
            'content' => MetrixIndicationsHistoryView::widget(['id'=>$counter->id,]),
        ],
        [
            'label' => Yii::t('metrix','alerts_log'),
            'content' => MetrixAlertsOneTypeWidget::widget(['id' => $counter->id]),
        ],
    ],

]);