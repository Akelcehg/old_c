<?php


use app\assets\AdminAppAsset;
use app\models\User;
use yii\bootstrap\Tabs;
use yii\widgets\PjaxAsset;

AdminAppAsset::register($this);
PjaxAsset::register($this);

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/corrector/CorrectorScripts.js', ['position' => 2]);
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/prom/PromScripts.js', ['position' => 2]);

?>

<div id="content">
    <div class="row" style="text-align: center">
         <span style="float: left">
            <b>
                <a href="<?= $url ?>"><< <?= Yii::t('prom', 'List of correctors') ?></a>
            </b>
        </span>
        <h1 id="correctorAddress"><?= $cc->address->fullAddressWithCity ?></h1>
    </div>
    <div class="row">

        <?= $this->render('/layouts/partials/jarviswidget', [
            'class' => 'jarviswidget-color-blue',
            'header' => $this->render('/layouts/partials/jarviswidget/title', ['title' => Yii::t('prom', 'Monitoring')], true),
            'control' => $this->render('/layouts/partials/jarviswidget/control', [
                'buttons' => []]),
            'content' => $this->render("_oneCorrectorTop", ['cc' => $cc, 'md' => $md, 'stg' => $stg, 'globalChartSettings' => $globalChartSettings])]);
        ?>



    </div>

    <div class="row">

        <div class="widget-body  bordered">


            <?php

            $items = [
                [
                    'label' => Yii::t('prom', 'Indication'),//'Показания',
                    'content' => $this->render("momentData", ['counterId' => $id]),
                    'active' => true
                ],

                [
                    'label' => Yii::t('prom', 'Charts'),//'Графики',
                    'options' => ['id' => 'chart', 'style' => 'height:auto;'],
                    'content' => $this->render('_charts', ['id' => $id]),
                ],
                [
                    'label' => Yii::t('prom', 'Contractor'),//'Контрагент',
                    'content' => $this->render("contragent", ['id' => $id, 'cc' => $cc]),

                ],
                [
                    'label' => Yii::t('prom', 'Reports'),//'Отчеты',
                    'content' => $this->render("reports", ['id' => $id, 'cc' => $cc]),

                ],
                [
                    'label' => Yii::t('prom', 'Accidents'),//'Аварии',
                    'content' => $this->render("emergency", ['counterId' => $id]),

                ],
                [
                    'label' => Yii::t('prom', 'Map'),//'Карта',
                    'options' => ['id' => 'map1', 'style' => 'height:330px;'],
                    'content' => \app\components\CounterMapOne::widget(['geo_location_id' => $correctorToCounter->geo_location_id]),

                ],

                [
                    'label' => Yii::t('prom', 'GSM modem'),//'GSM модем',
                    'content' => $this->render("_modem", ['counterId' => $id, 'correctorToCounter' => $correctorToCounter]),

                ],
                [
                    'label' => Yii::t('prom', 'Requests'),//'Запросы',
                    'content' => $this->render('_requests', ['card' => $correctorToCounter->simCard, 'modem' => $correctorToCounter]),
                ],
                [
                    'label' => Yii::t('prom', 'Photo'),//'Фото',
                    'content' => $this->render('_gallery', ['cc' => $correctorToCounter, 'imagesArray' => $imagesArray]),
                ],


            ];

            $log = [
                'label' => Yii::t('prom', 'Connect log'),//'Журнал Соединений',
                'content' => \app\modules\prom\components\ConnectWidget::widget(['id' => $correctorToCounter->modem_id]),
            ];

            if (User::is('superadmin')) {
                $items = array_merge($items, [$log]);

            }

            echo Tabs::widget(['items' => $items]);

            ?>

        </div>

    </div>

</div>



