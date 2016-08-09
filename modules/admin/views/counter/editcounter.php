
<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\Buttons;

/*$this->pageTitle = $pageTitle;
$this->breadcrumbs = array(
    'Administration' => array('administration/index'),
    $pageTitle
);*/
?>


<div id="content">  
    
<?php 
$flash = Yii::$app->session->getFlash('save');
?>
    <?php if (!empty($flash)): ?>
        <div class="flash-success alert alert-success">
    <?php echo $flash; ?>
        </div>
<?php endif; ?>    
    
    
<?php
echo $this->render('/layouts/partials/h1',array('title'=>Yii::t('counter','counter_edit'),'icon'=>'user'));
    echo $this->render('/layouts/partials/jarviswidget', array(
        'class'=>'jarviswidget-color-blue',
        'header' =>$this->render('/layouts/partials/jarviswidget/title', array('title' =>Yii::t('counter','counter_edit')), true),
        'control' =>$this->render('/layouts/partials/jarviswidget/control',[
            'buttons'=>
                Buttons::getButton($counter,['id'=>['icon'=>"fa fa-clock-o",'label'=>Yii::t('counter','counter'),'url'=>'admin/counter/editcounter/','attribute'=>'id',
                                                        'attributeValue'=>'id','class'=>'button-icon','style'=>'padding: 0 10px;background-color :#C1FFC1',],
                                            'indicationsHistory'=> ['icon'=>"fa fa-clock-o",'label'=>Yii::t('indications','IndicationsHistory'),'url'=>'admin/counter/getindications','attribute'=>'counter_id',
                                                        'attributeValue'=>'id','class'=>'button-icon','style'=>'padding: 0 10px;background-color :#FFE7BA;'],
                                            'rmodule'=>['icon'=>"fa fa-rss",'label'=>Yii::t('counter','rmodule'),'url'=>'admin/rmodule/editrmodule/','attribute'=>'serial_number',
                                                    'attributeValue'=>'rmodule_id','class'=>'button-icon','style'=>'padding: 0 10px;background-color :#FFE7BA;',],
                                            'modem'=>['icon'=>"fa fa-upload",'label'=>Yii::t('counter','modem'),'url'=>'admin/modem/editmodem/','attribute'=>'serial_number',
                                                    'attributeValue'=>'modem_id','class'=>'button-icon','style'=>'padding: 0 10px;background-color :#FFE7BA;',]])

        ],true),
        'content' =>$this->render('_editCounter',['counter'=>$counter, 'userRoles' => $userRoles] , true)
    ));
    ?>  
    
</div>
