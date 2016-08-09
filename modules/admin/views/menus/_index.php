<?php 
use yii\jui\Dialog;
use yii\bootstrap\Button;
use yii\helpers\Html;
use app\components\CustomTabView;

?>
<div id="add_menu" class="pull-left hidden">


    <?php
    Button::begin( array(
        'label' => 'addCategory',
        'options' => [
            'caption' => 'Add new Category',
            'onclick' => "js:function(){ajaxEditClickJstree('" . Yii::$app->urlManager->createUrl('add', array('parent' => 1, 'owner_id' => 0)) . "');}",
            'buttonType' => 'link',
            ]
    ));

    echo Html::beginForm(Yii::$app->urlManager->createUrl('/'), 'get', array('id' => 'filterForm'));
    echo "<div>";

    echo Html::tag('hr');

    echo "</div>";
    echo Html::endForm();
    ?>
</div>

<div class="clearfix"></div>
<div class="row">
    <div id="jstree_holder" class="col-xs-6">
        <ol id="jstree"></ol>
    </div>


    <div id="menu_box_container" class="col-xs-6">
        <div id="menu_box" class="col-xs-12">


            <?php
            $attributesDefaultValue = 'N/A';
            CustomTabView::begin(array(
                'activeTab' => 'add',
                'containerClass' => 'tab-content col-xs-12',
                'tabs' => array(
                    'add' => array(
                        'title' => 'Add',
                        'content' => $this->render('_add', ['menuList'=>$menuList], true),
                    ),
                    'edit' => array(
                        'title' => 'Edit',
                        'content' => $this->render('_edit', ['menuList'=>$menuList], true),
                    ),
                    'delete' => array(
                        'title' => 'Delete',
                        'content' => $this->render('_delete', ['menuList'=>$menuList], true),
                    )
                ),
            ));
            ?>


        </div>
    </div>
</div>
<input type="hidden" id="owner_id" value="0">
<input type="hidden" id="delete_menu_id" value="0">