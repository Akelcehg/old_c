
<?php

use yii\helpers\Html;
use app\assets\AdminAppAsset;
use yii\helpers\Url;
use yii\widgets\PjaxAsset;

AdminAppAsset::register($this);
PjaxAsset::register($this);

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/counter/CounterScripts.js', ['position' => 2]);

?>
<div id="content">
    <section id="widget-grid">
        <div class="row" >&nbsp;</div>

        <div class="row" >
                <?php
                echo $this->render('/layouts/partials/jarviswidget', array(
                    'class' => 'jarviswidget-color-blue',
                    'header' =>
                    $this->render('/layouts/partials/jarviswidget/title', array(
                        'title' => 'Список Радиомодулей'
                            ), true),
                    'control' => $this->render('/layouts/partials/jarviswidget/control', [
                        'buttons' => []
                           ]),
                    'content' => $this->render('_rmoduleList', [
                        'dataProvider' => $dataProvider,
                        'model' => $rmodule,
                        'searchModel' => $searchModel,
                            ], true)
                ));
                ?>
        </div>
</div>

</section>
