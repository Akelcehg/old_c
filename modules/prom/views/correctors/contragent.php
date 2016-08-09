<?php

use yii\bootstrap\Tabs;

echo Tabs::widget([

    'items' => [
        [
            'label' => Yii::t('prom','Contract'),//'Договор',
            'content' => $this->render("dogovor",['cc'=>$cc]),
            'active' => true
        ],
        [
            'label' => Yii::t('prom','Status'),//'Cостояние',
            'content' =>$this->render('_hw_options',['corrector'=>$cc]),
        ],
        [
            'label' => Yii::t('prom','Limits of gas consumption'),//'Лимиты расхода газа',
            'content' =>$this->render('limits',['cc'=>$cc]),
        ],
        [
            'label' => Yii::t('prom','History Limits'),//'История Лимитов',
            'content' =>\app\modules\prom\components\Limit\widgets\LimitHistoryWidget::widget(['all_id'=>$cc->id]),
        ]
    ]
]);




