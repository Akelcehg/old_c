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


    <?=\app\components\TabMenu::widget(['alias'=>'tabmenu1'])?>
    <section id="widget-grid">

   <div class="row">
            <?php
            echo $this->render('/layouts/partials/jarviswidget', [
                'class' => 'jarviswidget-color-blue',
                'header' => $this->render('/layouts/partials/jarviswidget/title', ['title' => Yii::t('prom','Monitoring')], true),
                'control' => $this->render('/layouts/partials/jarviswidget/control', [
                    'buttons' => [
                        /*Html::a('Список корректоров ', Yii::$app->urlManager->createUrl(['/prom/correctors']), array(
                        'class' => 'button-icon ',
                        'style' => 'padding: 0 10px;background-color :#FFE7BA;',
                    )),*/
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
                    'title' => Yii::t('prom','List of correctors')
                ), true),
            'control' => $this->render('/layouts/partials/jarviswidget/control', array(
            ), true),
            'content' => $this->render('_correctorList', [
                'dataProvider' => $dataProvider,


            ], true)
        ));
        ?>




    </div>
    </div>
</div>

</section>
