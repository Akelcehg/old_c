
<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/*$this->pageTitle = $pageTitle;
$this->breadcrumbs = array(
    'Administration' => array('administration/index'),
    $pageTitle
);*/
?>


<div id="content">    
<?php echo $this->render('/layouts/partials/h1',array('title'=>'Добавить Коррекцию','icon'=>'user'));

   echo $this->render('/layouts/partials/jarviswidget', array(
        'class'=>'jarviswidget-color-blue',
        'header' =>
            $this->render('/layouts/partials/jarviswidget/title', array(
                'title' => 'Добавить Коррекцию'
            ), true),
        'control' => $this->render('/layouts/partials/jarviswidget/control',[],true),
        'content' =>$this->render('_addCorrection',['oldIndication'=>$oldIndication,'indication'=>$indication] , true)
    ));
    ?>
    
</div>
