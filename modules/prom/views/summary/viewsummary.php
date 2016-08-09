<?php


use yii\helpers\Html;
use app\assets\AdminAppAsset;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\PjaxAsset;

AdminAppAsset::register($this);
PjaxAsset::register($this);

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/corrector/SummaryScripts.js', ['position' => 2]);

?>
<div id="content">
    <?=\app\components\TabMenu::widget(['alias'=>'tabmenu1'])?>
    <section id="widget-grid">
        <div class="row">
            <div style="padding-left: 15%;padding-right: 15%">
            <?php $form = ActiveForm::begin(['id' => 'form','action'=>Yii::$app->urlManager->createUrl(['/prom/summary/loadsummary','id'=>Yii::$app->request->get('id',false)]), 'options' => ['enctype' => 'multipart/form-data']]); ?>
            <?=$this->render("_viewsummary",['summary'=>$summary])?>
               <div style="width:100%;text-align: center"> <?=Html::submitButton("скачать PDF",["class"=>"btn btn-primary"])?></div>
                    <?php ActiveForm::end() ?>
            </div>
        </div>
    </section>
</div>


