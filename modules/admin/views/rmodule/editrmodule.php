
<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/*$this->pageTitle = $pageTitle;
$this->breadcrumbs = array(
    'Administration' => array('administration/index'),
    $pageTitle
);*/

$rmoduleTab=$rmodule->getOldAttributes();

?>


<div id="content">    
<?php echo $this->render('/layouts/partials/h1',array('title'=>Yii::t('counter','rmodule_edit'),'icon'=>'user'));
   
    
    echo $this->render('/layouts/partials/jarviswidget', array(
        'class'=>'jarviswidget-color-blue',
        'header' =>$this->render('/layouts/partials/jarviswidget/title', array('title' => Yii::t('counter','rmodule_edit')), true),
        'control' =>$this->render('/layouts/partials/jarviswidget/control',[
            'buttons'=>[
                Html::a('<i class="fa fa-clock-o"></i> '.Yii::t('counter','counter'),
                    Yii::$app->urlManager->createUrl([
                        'admin/counter/editcounter/',
                        'id'=>$rmoduleTab['counter_id']]), array(
                        'class' => 'button-icon',
                        'style' => 'padding: 0 10px;background-color :#FFE7BA;',
                    )),

                Html::a('<i class="fa fa-rss"></i> '.Yii::t('counter','rmodule'),
                    Yii::$app->urlManager->createUrl([
                        'admin/rmodule/editrmodule/',
                        'serial_number'=>$rmoduleTab['serial_number']]), array(
                        'class' => 'button-icon',
                        'style' => 'padding: 0 10px;background-color :#C1FFC1;',
                    )),
                Html::a('<i class="fa fa-upload"></i> '.Yii::t('counter','modem'),
                    Yii::$app->urlManager->createUrl([
                        'admin/modem/editmodem/',
                        'serial_number'=>$rmoduleTab['modem_id']]), array(
                        'class' => 'button-icon',
                        'style' => 'padding: 0 10px;background-color :#FFE7BA;',
                    )),
            ]
        ],true),
        'content' =>$this->render('_editRmodule',['rmodule'=>$rmodule] , true)
    ));

    
    ?>  
    
</div>
