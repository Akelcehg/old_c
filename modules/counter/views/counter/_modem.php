<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 10.03.16
 * Time: 9:47
 */

use yii\bootstrap\Tabs;

echo Tabs::widget([

    'items' => [
        [
            'label' => 'Модем',
            'content' =>$this->render('_editModem',['modem'=>$modem]),
        ],
        [
            'label' => 'SIM',
            'content' =>$this->render('_editSim',['card'=>$modem->simCard,'modem'=>$modem]),
        ],
         [
            'label' => 'Запросы',
            'content' =>$this->render('_requests',['card'=>$modem->simCard,'modem'=>$modem]),
        ],
        [
            'label' => 'История баланса',
            'content' =>\app\modules\counter\components\BalanceHistoryView::widget(['serial_number'=>$modem->serial_number]),
        ],
        [
            'label' => 'История SMS',
            'content' =>\app\modules\counter\components\SmsHistoryView::widget(['serial_number'=>$modem->serial_number]),
        ],
    ],

]);