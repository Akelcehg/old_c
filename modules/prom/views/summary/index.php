<?php


use yii\helpers\Html;
use app\assets\AdminAppAsset;
use yii\helpers\Url;
use yii\widgets\PjaxAsset;

AdminAppAsset::register($this);
PjaxAsset::register($this);
/* $this->pageTitle = $pageTitle;
  $this->breadcrumbs = array(
  'Administration' => array('administration/index'),
  $pageTitle
  ); */

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/chart/Chart_yiir_summary.js');

?>
<div id="content">
    <?=\app\components\TabMenu::widget(['alias'=>'tabmenu1'])?>
    <section id="widget-grid">

        <div class="row">

            <div style="width: 100%">

                <div style="width: 100%;float: left">

                <?= $this->render('/layouts/partials/jarviswidget', [
                    'class' => 'jarviswidget-color-blue',
                    'header' => $this->render('/layouts/partials/jarviswidget/title', ['title' => Yii::t('prom','GRS')], true),
                    'control' => $this->render('/layouts/partials/jarviswidget/control', [
                        'buttons' => [
                            /*Html::a('Список корректоров ', Yii::$app->urlManager->createUrl(['/prom/correctors']), array(
                            'class' => 'button-icon ',
                            'style' => 'padding: 0 10px;background-color :#FFE7BA;',
                            )),*/
                        ]]),

                    'content' => $this->render('_summaryDay1', ['globalChartSettings' => $globalChartSettings])
                ]); ?>

            </div>



        </div>

</div>

        <div class="row">

            <div style="width: 100%">

                <div style="width: 100%;float: left">

                    <?= $this->render('/layouts/partials/jarviswidget', [
                        'class' => 'jarviswidget-color-blue',
                        'header' => $this->render('/layouts/partials/jarviswidget/title', ['title' =>Yii::t('prom','ConsumpTotal')], true),
                        'control' => $this->render('/layouts/partials/jarviswidget/control', [
                            'buttons' => [
                                /*Html::a('Список корректоров ', Yii::$app->urlManager->createUrl(['/prom/correctors']), array(
                                'class' => 'button-icon ',
                                'style' => 'padding: 0 10px;background-color :#FFE7BA;',
                                )),*/
                            ]]),

                        'content' => $this->render('_summaryDay3', ['globalChartSettings' => $globalChartSettings])
                    ]); ?>

                </div>



            </div>

        </div>
<div class="row">

    <div style="width: 100%">
        <div style="width: 50%;float: left">

            <?= $this->render('/layouts/partials/jarviswidget', [
                'class' => 'jarviswidget-color-blue',
                'header' => $this->render('/layouts/partials/jarviswidget/title', ['title' => Yii::t('prom','Industry')], true),
                'control' => $this->render('/layouts/partials/jarviswidget/control', [
                    'buttons' => [
                        /*Html::a('Список корректоров ', Yii::$app->urlManager->createUrl(['/prom/correctors']), array(
                        'class' => 'button-icon ',
                        'style' => 'padding: 0 10px;background-color :#FFE7BA;',
                        )),*/
                    ]]),

                'content' => $this->render('_summaryDay2', ['globalChartSettings' => $globalChartSettings])
            ]); ?>

        </div>
        <div style="width: 50% ;float: left"
        ">

        <?= $this->render('/layouts/partials/jarviswidget', [
            'class' => 'jarviswidget-color-blue',
            'header' => $this->render('/layouts/partials/jarviswidget/title', ['title' =>Yii::t('prom','HouseMetering')], true),
            'control' => $this->render('/layouts/partials/jarviswidget/control', [
                'buttons' => [
                    /*Html::a('Список корректоров ', Yii::$app->urlManager->createUrl(['/prom/correctors']), array(
                    'class' => 'button-icon ',
                    'style' => 'padding: 0 10px;background-color :#FFE7BA;',
                    )),*/
                ]]),

            'content' => $this->render('_summaryDayCounter1', ['globalChartSettings' => $globalChartSettings])
        ]); ?>

    </div>


    <div style="width: 50%;float: left">

        <?= $this->render('/layouts/partials/jarviswidget', [
            'class' => 'jarviswidget-color-blue',
            'header' => $this->render('/layouts/partials/jarviswidget/title', ['title' =>Yii::t('prom','Combit')], true),
            'control' => $this->render('/layouts/partials/jarviswidget/control', [
                'buttons' => [
                    /*Html::a('Список корректоров ', Yii::$app->urlManager->createUrl(['/prom/correctors']), array(
                    'class' => 'button-icon ',
                    'style' => 'padding: 0 10px;background-color :#FFE7BA;',
                    )),*/
                ]]),

            'content' => $this->render('_summaryDayCounter2', ['globalChartSettings' => $globalChartSettings])
        ]); ?>

    </div>
</div>

</div>
</section>


