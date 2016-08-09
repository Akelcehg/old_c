<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\FontAwasome;
use yii\helpers\ArrayHelper;
?>
<div class="content" id="content_add">

    <div class="widget-body">

        <div class="form-horizontal styled-form">
            <div class="form-group" style="margin-top: 8px;">
                <?php echo Html::label('Загаловок', 'add_title', array('class' => 'col-xs-6 control-label')); ?>
                <div class="col-xs-6">
                    <?php
                    echo Html::textInput('add_title', '', array('class' => 'form-control', 'id' => 'add_title',));
                    ?>
                </div>
            </div>

            <div class="form-group" style="margin-top: 8px;">
                <?php echo Html::label('Url', 'add_url', array('class' => 'col-xs-6 control-label')); ?>
                <div class="col-xs-6">
                    <?php echo Html::textInput('add_url', '', array('class' => 'form-control', 'id' => 'add_url',)); ?>
                </div>
            </div>

            <?php /*  <div class="form-group" style="margin-top: 8px;">
              <?php echo Html::label('Login state', 'add_login_state_id', array('class' => 'col-xs-6 control-label')); ?>
              <div class="col-xs-6">
              <?php
              echo Html::dropDownList('add_login_state_id', '', Html::listData($loginStates, 'id', 'name'), array(
              'class' => 'form-control',
              ));
              ?>
              </div>
              </div>
             */ ?>
            <div class="form-group">
                <?php echo Html::label('User group access', 'add_title', array('class' => 'col-xs-6 control-label')); ?>
                <div class="col-xs-6">

                    <div class="inline-group">
                        <?php
                        echo Html::checkBoxList('menuAccess', false, ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name'), array(
                            'class' => 'menuAccessAdd checkbox',
                            'itemOptions' => ['class' => 'menuAccessAdd',],
                            //'template' => '<label class="checkbox">{input}<span></span> {label}</label>',
                            'item' => function ($index, $label, $name, $checked, $value) {
                                            echo '<label class="checkbox">'
                                            . '<input class="menuAccessAdd" type="checkbox" value="' . $value . '" name="' . $name . '">'
                                            . '<span>' . $label . '</span>'
                                            . '</label>';
                                        },
                            'labelOptions' => array('class' => 'no-padding'),
                            'separator' => ''
                        ));
                        ?>
                    </div>
                    <div class="pull-right">
                        <?php
                        echo Html::button('Check all', array(
                            'class' => 'btn btn-default btn-xs',
                            'onClick' => "$('.menuAccessAdd').prop( 'checked', true );"
                        ));
                        ?>
                    </div>
                </div>
            </div> 

            <div class="form-group icons-container">
                <?php echo Html::label('Иконка', 'icon_add', array('class' => 'col-xs-6 control-label')); ?>
                <div class="col-xs-6">
                    <div class="icons-selector">
                        <?php
                        echo Html::radioList('icon_add', '', FontAwasome::getKeyImages(), array(
                            'class' => 'radiobox',
                            'separator' => '',
                            'template' => '<label style="margin-right: 14px">{input}<span style="margin-right: 0;"></span> {label}</label>',
                            'item' => function ($index, $label, $name, $checked, $value) {
                                echo '<label style="margin-right: 14px">'
                                . '<input id="icon_add_' . $index . '" class="radiobox" type="radio" name="' . $name . '" checked="' . $checked . '" value="' . $value . '">'
                                . '<span style="margin-right: 0;"></span>'
                                . '<label for="icon_add_' . $index . '">' . $label . '</label>'
                                . '</label>';
                            }
                        ));
                        ?>
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-top: 8px;">
                <?php echo Html::label('Новое окно', 'add_new_window', array('class' => 'col-xs-6 control-label')); ?>
                <div class="col-xs-6">
                    <label class="checkbox">
                        <?php echo Html::checkBox('add_new_window', '', array('class' => 'checkbox form-control')); ?>
                        <span></span>
                    </label>
                </div>
            </div>

            <div class="form-group" style="margin-top: 8px;">
                <?php echo Html::label('Невидимый', 'add_hide', array('class' => 'col-xs-6 control-label')); ?>
                <div class="col-xs-6">
                    <label class="checkbox">
                        <?php echo Html::checkBox('add_hide', '', array('class' => 'checkbox form-control')); ?>
                        <span></span>
                    </label>
                </div>
            </div>


            <div class="form-group">
                <div class="title col-xs-6">Подкатегория :<br><a href="javascript:;" class="btn btn-default btn-xs"
                                                                 id="add_as_main">Добавить как корневую категорию</a></div>
                <div class="field add_to_menu col-xs-6"></div>

            </div>

            <div class="form-group">
                <div class="field col-xs-6 pull-right" id="add_menu_button">
                    <a href="javascript:;" onClick="addMenu();" class="btn btn-primary col-xs-12">Добавить меню</a>
                </div>
            </div>


        </div>
    </div>
</div>
<div class="clearfix"></div>