
<?php

use app\components\Buttons;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/*$this->pageTitle = $pageTitle;
$this->breadcrumbs = array(
    'Administration' => array('administration/index'),
    $pageTitle
);*/

?>


<div id="content">    
<?php echo $this->render('/layouts/partials/h1',array('title'=>Yii::t('counter','modem_edit'),'icon'=>'user'));
   
    
    echo $this->render('/layouts/partials/jarviswidget', array(
        'class'=>'jarviswidget-color-blue',
        'header' =>$this->render('/layouts/partials/jarviswidget/title', array('title' => Yii::t('counter','modem_edit')), true),
        'control' =>$this->render('/layouts/partials/jarviswidget/control',[
            'buttons'=>[
                Html::a('<i class="fa fa-lg fa-file"></i> Sim',
                    Yii::$app->urlManager->createUrl([
                        'admin/modem/editsimcard/',
                        'modem_id'=>$modem->serial_number]), array(
                        'class' => 'button-icon',
                        'style' => 'padding: 0 10px;background-color :#FFE7BA;',
                    )),
        ]],true),
        'content' =>$this->render('_editModem',['modem'=>$modem] , true)
    ));
    
     echo $this->render('/layouts/partials/jarviswidget', array(
        'class'=>'jarviswidget-color-blue',
        'header' =>$this->render('/layouts/partials/jarviswidget/title', array('title' => Yii::t('counter','counters_list')), true),
        'control' =>$this->render('/layouts/partials/jarviswidget/control',[],true),
        'content' =>$this->render('_countersList',['counters'=>$counters,'gridViewId'=>'counters-grid'] , true)
    ));
    
    ?>  
    
</div>
