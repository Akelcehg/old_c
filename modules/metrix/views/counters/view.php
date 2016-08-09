<?php


use app\assets\AdminAppAsset;
use app\models\CorrectorToCounter;
use app\models\MomentData;
use app\models\User;
use app\modules\counter\components\EventCalendar;
use app\modules\metrix\components\MetrixAlertsOneTypeWidget;
use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\widgets\PjaxAsset;

AdminAppAsset::register($this);
PjaxAsset::register($this);
?>

<div id="content">
    <div class="row" style="text-align: center">
            <span style="float: left">
            <b>
                <a href="<?= $url ?>"><< <?=  Yii::t('metrix','metrix_list') ?></a>
            </b>
        </span>
        <h1 id="correctorAddress"><?=$counter->fulladdresswithcity?></h1>
    </div>


    <div class="row">

        <?= $this->render('/layouts/partials/jarviswidget', [
            'class' => 'jarviswidget-color-blue',
            'header' => $this->render('/layouts/partials/jarviswidget/title', ['title' => Yii::t('metrix', 'Monitoring')], true),
            'control' => $this->render('/layouts/partials/jarviswidget/control', [
                'buttons' => []]),
            'content' => $this->render("_oneCounterTop", ['counter' => $counter, 'labels' => $labels, 'globalChartSettings' => $globalChartSettings])]);
        ?>

    </div>

    <div class="row">

        <div class="widget-body  bordered" data-container="body">

            <?php $items=[
                [
                    'label' => Yii::t('metrix', 'Indication'),
                    'content' => $this->render('_indication', ['counter' => $counter]),
                    'active' => true
                ],
                [
                    'label' => Yii::t('metrix', 'Charts'),
                    //'headerOptions' => ['id'=>'charts'],
                    'options' => ['id' => 'chart','style'=>'height:auto;'],
                    'content' => $this->render('_charts', ['id' => $counter->id]),
                ],
                [
                    'label' => Yii::t('metrix', 'Contractor'),//'Контрагент',
                    'content' => $this->render("_contragent", ['id' => $counter->corrector->id, 'cc' => $counter->corrector]),

                ],

                [
                    'label' => Yii::t('metrix', 'Reports'),
                    'content' => $this->render('_reports',['counter1'=>$counter]),

                ],

                [
                    'label' => Yii::t('metrix', 'Accidents'),
                    'content' => MetrixAlertsOneTypeWidget::widget(['id' => $counter->id,'status' => 'ACTIVE']),

                ],
                [
                    'label' => Yii::t('metrix', 'Map'),
                    'options' => ['id' => 'map1','style'=>'height:330px;'],
                    'content' => \app\components\CounterMapOne::widget(['geo_location_id' =>  $counter->geo_location_id]),

                ],
                [
                    'label' => Yii::t('metrix', 'Counter'),
                    'content' => $this->render('_editCounter', ['counter' => $counter, 'userRoles' => $userRoles]),

                ],
                [
                    'label' => Yii::t('metrix', 'GSM modem'),
                    'content' => $this->render('_modem', ['counter' => $counter]),

                ],
                [
                    'label' => Yii::t('metrix', 'Options'),
                    'content' => $this->render('_options', ['counter' => $counter]),
                    'options'=>['id'=>'optionsTab']

                ],
                [
                    'label' => Yii::t('metrix', 'Traffic'),
                    'content' => $this->render('_traffic', ['counter' => $counter]),
                    'options'=>['id'=>'trafficTab']

                ],
               /* [
                    'label' => 'Календарь',
                    'content' => EventCalendar::widget(),
                    'options'=>['id'=>'calendarWidget']

                ],*/

            ];

            $log = [
                'label' => Yii::t('prom', 'Connect log'),//'Журнал Соединений',
                'content' => \app\modules\prom\components\ConnectWidget::widget(['id' => $counter->corrector->modem_id]),
            ];

            if (User::is('superadmin')) {
                $items = array_merge($items, [$log]);

            }
            echo Tabs::widget([ 'items' =>$items]);
            ?>

        </div>

    </div>

</div>




