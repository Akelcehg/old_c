
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

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/counter/CounterScripts.js', ['position' => 2]);

?>
<div id="content">


    <section id="widget-grid">
        <div class="row" >
            <?php
            echo $this->render('/layouts/partials/jarviswidget', array(
                'class' => 'jarviswidget-color-blue',
                'header' =>
                    $this->render('/layouts/partials/jarviswidget/title', array(
                        'title' => 'Список команд в конвеере'
                    ), true),
                'control' => $this->render('/layouts/partials/jarviswidget/control', array(
                ), true),
                'content' => $this->render('_commandList', [
                    'dataProvider' => $dataProvider,
                    'searchModel'=>$searchModel


                ], true)
            ));
            ?>




        </div>
</div>

</section>
