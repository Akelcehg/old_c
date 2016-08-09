
<?php

use yii\helpers\Html;
use app\assets\AdminAppAsset;
use yii\helpers\Url;
use yii\jui\DatePicker;
AdminAppAsset::register($this);

/*$this->pageTitle = $pageTitle;
$this->breadcrumbs = array(
    'Administration' => array('administration/index'),
    $pageTitle
);*/

$this->registerJsFile(Yii::$app->request->baseUrl.'/js/counter/CounterScripts.js',['position'=>2]);



?>
<div id="content">
    <?php  $header = Yii::$app->request->get('type','gas');

        echo $this->render('/layouts/partials/h1',array('title'=>'Календарь' ,'icon'=>'user'));
       
     ?>


<section id="widget-grid">


    <div class="row"> 
        <div style="float: left;width:49%">
    <?php
   echo $this->render('/layouts/partials/jarviswidget', array(
        'class'=>'jarviswidget-color-blue',
        'header' =>
            $this->render('/layouts/partials/jarviswidget/title', array(
                'title' => 'GSM модемы'
            ), true),
        'control' => $this->render('/layouts/partials/jarviswidget/control', array(), true),
        'content' =>$this->render('_counterEvents',[
            'dataProvider'=>$dataProvider,
            'searchModel' => $searchModel,
          
        ] , true)
    ));
   
  
    ?>
        </div>
       <div style="float: left;width:49%;margin-left: 15px;height: 100%"> 
        <?php
        echo \app\components\EventCalendar::widget();
        
         echo $this->render('/layouts/partials/jarviswidget', array(
        'class'=>'jarviswidget-color-blue',
        'id'=>'synhroEditorWidget',
        'header' =>
            $this->render('/layouts/partials/jarviswidget/title', array(
                'title' => 'Редактор синхронизации <span> </span>'
            ), true),
        'control' => $this->render('/layouts/partials/jarviswidget/control', array(), true),
        'content' => ''
             . '<p style="margin:10px;">№<span style="float:right;margin-right:10px" id="serial_number"></span></p>'
             . '<p style="margin:10px;">Интервал(ч)<input style="float:right;margin-right:10px" type="text" id="update_interval"></p>'
             . '<p style="margin:10px;">Тип<select name="type" style="float:right;margin-right:10px" id="type">
                            <option disabled>Выберите тип</option>
                            <option value="once">По дате</option>
                            <option value="every_day">Каждый день</option>
                            </select></p>'
             . '<p style="margin:10px;">Гарантированный выход'
             .DatePicker::widget(['dateFormat' => 'MM-dd','clientOptions' => ['nextText' => '>','prevText' => '<'],'options' => [ 'id' => 'month_update',"style"=>"float:right;margin-right:10px"]])
             . '</p>'
             . '<input type="button" id="submit" value="Сохранить">'
             )); 
        
        
        ?>
        </div>
    </div>
</div>

</section>
