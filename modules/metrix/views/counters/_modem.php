<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 16.03.16
 * Time: 11:59
 */

use yii\bootstrap\Tabs;

echo Tabs::widget([

    'items' => [
        [
            'label' => Yii::t('metrix', 'modem'),
            'content' => $this->render("_gsmModem", ['modemStatus' => $counter->modemStatus]),
            'active' => true
        ],
        [
            'label' => 'SIM',
            'content' => $this->render('_editSim', ['card' => $counter->simCard, 'modem' => $counter->modemStatus]),
        ],
        [
            'label' => Yii::t('prom', 'Balance history'),// 'История баланса',
            'content' => \app\modules\counter\components\BalanceHistoryView::widget(['serial_number' => $counter->corrector->modem_id]),
        ],
        [
            'label' => Yii::t('prom', 'SMS history'),//'История SMS',
            'content' => \app\modules\counter\components\SmsHistoryView::widget(['serial_number' => $counter->corrector->modem_id]),
        ],
        [
            'label' => Yii::t('metrix', 'modem_options'),
            'content' => $this->render("_modem_options", ['counter'=>$counter,'corrector' => $counter->corrector]),
        ],

    ]
]);