
<?php

use yii\helpers\Html;

/*$this->pageTitle = $pageTitle;
$this->breadcrumbs = array(
    'Administration' => array('administration/index'),
    $pageTitle
);*/
?>

<div id="content">
    <?php echo $this->render('/layouts/partials/h1',array('title'=>Yii::t('users','users'),'icon'=>'user')); ?>

    <!-- NEW WIDGET START -->
    <?php
$arr=['dataProvider'=>$dataProvider,'model'=>$userList];  //get_defined_vars();
   echo $this->render('/layouts/partials/jarviswidget', array(
        'class'=>'jarviswidget-color-blue',
        'header' =>
            $this->render('/layouts/partials/jarviswidget/title', array(
                'title' => Yii::t('users','users')
            ), true),
        'control' => $this->render('/layouts/partials/jarviswidget/control', array(
            'buttons' => array(
               Html::a('<i class="fa fa-columns"></i> Export', '#', array(
                    'class' => 'button-icon jarviswidget-toggle-btn btn-export-excel',
                    'onClick' => 'exportFilteredUsers(\'approved\'); return false;',
                    'style' => 'padding: 0 10px;',
                ))
            )
        ), true),
        'content' =>$this->render('_usersList',$arr , true)
    ));
    ?>

</div>
