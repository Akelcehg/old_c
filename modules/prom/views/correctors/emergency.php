<?php use yii\bootstrap\Tabs;
echo Tabs::widget([

    'items' => [
        [
            'label' => Yii::t('prom','Accidents'),//'Аварии',
            'content' => $this->render("_emergency",['counterId'=>$counterId]),

        ],

        [
            'label' => Yii::t('prom','Diagnostic Comms'),//'Диагностика',
            'content' =>$this->render("_diagnostic",['counterId'=>$counterId]),

        ],
        [
            'label' => Yii::t('prom','Interventions'),//'Вмешательства',
            'content' =>$this->render("_intervention",['counterId'=>$counterId]),

        ],
    ],
]);