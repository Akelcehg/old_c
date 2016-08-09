
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
setlocale(LC_ALL ,"russian");

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/corrector/CorrectorScripts.js', ['position' => 2]);

?>
<div id="content">

        <div class="row" style="text-align: center">
            <h1 id="correctorAddress">Экспорт</h1>
        </div>



        <div  style="text-align: center;width: 100%">
            <div style="margin:0 auto;width: 100%">
                <table class='table-striped table-hover table-bordered' id='momentDataTable' style="margin:0 auto;">
                    <tr>
                        <td>Выгрузка данных промышленных коррeкторов в формате dbf</td>
                        <td> <a href="#" id = "exportCounter1Cm">За текущий  месяц<?=strftime (" %B")?></a></td>
                    </tr>
                </table>
            </div>
        </div>

</div>


