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
            'label' =>Yii::t('prom','Modem'),// 'Модем',
            'content' => $this->render("gsmModem",['counterId'=>$counterId]),
            'active' => true
        ],
        [
            'label' => Yii::t('prom','SIM'),// 'SIM',
            'content' =>$this->render('_editSim',['card'=>$correctorToCounter->simCard,'modem'=>$correctorToCounter]),
        ],

        [
            'label' => Yii::t('prom','Balance history'),// 'История баланса',
            'content' =>\app\modules\counter\components\BalanceHistoryView::widget(['serial_number'=>$correctorToCounter->modem_id]),
        ],
        [
            'label' => Yii::t('prom','SMS history'),//'История SMS',
            'content' =>\app\modules\counter\components\SmsHistoryView::widget(['serial_number'=>$correctorToCounter->modem_id]),
        ],
    ]
]);