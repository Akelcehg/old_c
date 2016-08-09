
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

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/corrector/CorrectorScripts.js', ['position' => 2]);

?>
<div id="content">


    <section id="widget-grid">
         <?=\app\modules\prom\components\NavTabsWidget::widget()?>
        <div class="row">
            <?php
            echo $this->render('/layouts/partials/jarviswidget', [
                'class' => 'jarviswidget-color-blue',
                'header' => $this->render('/layouts/partials/jarviswidget/title', ['title' => 'Мониторинг'], true),
                'control' => $this->render('/layouts/partials/jarviswidget/control', [
                    'buttons' => [ Html::a('Список корректоров ', Yii::$app->urlManager->createUrl(['/prom/correctors']), array(
                        'class' => 'button-icon ',
                        'style' => 'padding: 0 10px;background-color :#FFE7BA;',
                    )),
                    ] ]),

                'content' => $this->render('_summaryRT',['globalChartSettings'=>$globalChartSettings])
            ]);
            ?>


        </div>

        <div class="row" >
            <?php
            echo $this->render('/layouts/partials/jarviswidget', array(
                'class' => 'jarviswidget-color-blue',
                'header' =>
                    $this->render('/layouts/partials/jarviswidget/title', array(
                        'title' => 'Список вмешательсв по корректорам '
                    ), true),
                'control' => $this->render('/layouts/partials/jarviswidget/control',[
                  'buttons' =>[
                     ]

                ], true),
                'content' => $this->render('_interventionList', [
                    'dataProvider' => $output,
                ], true)
            ));
            ?>




        </div>
</div>

</section>
