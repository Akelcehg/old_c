<?php

use yii\helpers\Html;
use app\assets\AdminAppAsset;
use yii\helpers\Url;
use yii\widgets\PjaxAsset;

AdminAppAsset::register($this);
PjaxAsset::register($this);

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/counter/CounterScripts.js', ['position' => 2]);
$this->registerJsFile(Yii::$app->request->baseUrl . 'Z:\home\counter.test\vendor\bower\yii2-pjax\jquery.pjax.js/jquery.pjax.js', ['position' => 3]);
?>
<div id="content">
    <?php

        echo $this->render('/layouts/partials/h1', array('title' => Yii::t('address','AddressList'), 'icon' => 'user'));


    ?>
    <section id="widget-grid">
        <div class="row">
            <!-- NEW WIDGET START -->
            <div class="row">
                <?php
                echo $this->render('/layouts/partials/jarviswidget', array(
                    'class' => 'jarviswidget-color-blue',
                    'header' =>
                        $this->render('/layouts/partials/jarviswidget/title', array(
                            'title' => Yii::t('address','AddressList')
                        ), true),
                    'control' => $this->render('/layouts/partials/jarviswidget/control', array(
                        'buttons' => []
                    ), true),
                    'content' => $this->render('_addressList', [
                        'dataProvider' => $dataProvider,
                        'model' => $address,


                    ], true)
                ));
                ?>
            </div>
        </div>

    </section>
