
<?php

use app\assets\AdminAppAsset;

AdminAppAsset::register($this);

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/menus/menus_base.js');
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/jquery.ui.sortable.js');
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/menus/jstree.js');
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/menus/menus.js');

$this->registerCssFile(Yii::$app->request->baseUrl . '/css/menus/jstree.css');
$this->registerCssFile(Yii::$app->request->baseUrl . '/css/menus/menus.css');
?>

<div id="content">

    <?php $flash = Yii::$app->session->getFlash('menus'); ?>
    <?php if (!empty($flash)): ?>
        <div class="flash-success alert alert-success">
    <?php echo $flash; ?>
        </div>
<?php endif; ?>

    <!-- Custom message that is set on delete/move/etc. actions in the JS tree -->
    <div class="flash-success alert alert-success" id="custom_js_message" style="display: none;"></div>


    <?php echo $this->render('/layouts/partials/h1', array('title' => 'Меню', 'icon' => 'user')); ?>

    <?php
    echo $this->render('/layouts/partials/jarviswidget', array(
        'class' => 'jarviswidget-color-blue',
        'header' =>
        $this->render('/layouts/partials/jarviswidget/title', array(
            'title' => 'Меню'
                ), true),
        'content' => $this->render('_index', ['menuList' => $menuList], true)
    ));
    ?>

</div>


<?php //echo $this->render('_dialog'); ?>
