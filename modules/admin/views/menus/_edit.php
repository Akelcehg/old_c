<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\components\FontAwasome;
use yii\helpers\ArrayHelper;
use app\models\MenuItemsAccess;

?>
<div class="content" id="content_edit">

    <div class="widget-body">
                <div class="form-horizontal styled-form">
        <div class="form-group" style="margin-top: 8px;">
            <?php echo Html::label('Заголовок', 'edit_title', array('class' => 'col-xs-6 control-label')); ?>
            <div class="col-xs-6">
                <?php 
                
          

                    echo Html::textInput('edit_title', '', array(
                        'class' => 'form-control',
                         'id'=>'edit_title',
                            )
                    );
                
                ?>
            </div>
        </div>

        <div class="form-group" style="margin-top: 8px;">
            <?php echo Html::label('Url', 'edit_url', array('class' => 'col-xs-6 control-label')); ?>
            <div class="col-xs-6">
                <?php echo Html::textInput('edit_url', '', array('class' => 'form-control', 'id'=>'edit_url',)); ?>
            </div>
        </div>
        
        <?php /*<div class="form-group" style="margin-top: 8px;">
            <?php echo Html::label('Login state', 'edit_login_state_id', array('class' => 'col-xs-6 control-label')); ?>
            <div class="col-xs-6">
                <?php
                echo Html::dropDownList('edit_login_state_id', '', Html::listData($loginStates, 'id', 'name'), array(
                    'class' => 'form-control',
                ));
                ?>
            </div>
        </div>
*/?>
        <div class="form-group">
            <?php echo Html::label('User group access', 'edit_title', array('class' => 'col-xs-6 control-label')); ?>
            <div class="col-xs-6">

                <div class="inline-group">
                    <?php
                    
                   
                    
                    
                    echo Html::checkBoxList('menuAccess',false, ArrayHelper::map(Yii::$app->authManager->getRoles(),'name', 'name'), array(
                        'class' => 'menuAccessEdit',
                        'itemOptions'=>['class' => 'menuAccessEdit',],
                        'template' => '<label class="checkbox">{input}<span></span> {label}</label><br/>',
                        'item' =>  function ($index, $label, $name, $checked, $value) {
                                    echo '<label class="checkbox">'
                                            . '<input class="menuAccessEdit" type="checkbox" value="'.$value.'" name="'.$name.'">'
                                            . '<span>'.$label.'</span>'
                                            . '</label>';
                                    },
                        'labelOptions' => array('class' => 'no-pediting'),
                        'separator' => ''
                    ));
                    ?>
                </div>
                <div class="pull-right">
                    <?php
                    echo Html::button('Check all', array(
                        'class' => 'btn btn-default btn-xs',
                        'onClick' => "$('.menuAccessEdit').prop( 'checked', true );"
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group icons-container">
            <?php echo Html::label('Иконка', 'icon_edit', array('class' => 'col-xs-6 control-label')); ?>
            <div class="col-xs-6">
                <div class="icons-selector">
                    <?php //FontAwasome::getKeyImages()

                    echo Html::radioList('icon_edit', '', FontAwasome::getKeyImages(), array(
                        'class' => 'radiobox',
                        'separator' => '',
                        'template' => '<label style="margin-right: 14px">{input}<span style="margin-right: 0;"></span> {label}</label>',
                        'item'=> function ($index, $label, $name, $checked, $value)
                                    {
                                        echo '<label style="margin-right: 14px">'
                                           . '<input id="icon_edit_'.$index.'" class="radiobox" type="radio" name="'.$name.'" checked="'.$checked.'" value="'.$value.'">'
                                           . '<span style="margin-right: 0;"></span>'
                                           . '<label for="icon_edit_'.$index.'">'.$label.'</label>'     
                                           . '</label>';
                                    }
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="form-group" style="margin-top: 8px;">
            <?php echo Html::label('Новое окно', 'edit_new_window', array('class' => 'col-xs-6 control-label')); ?>
            <div class="col-xs-6">
                <label class="checkbox">
                    <?php echo Html::checkBox('edit_new_window', '', array('class' => 'checkbox form-control')); ?>
                    <span></span>
                </label>
            </div>
        </div>

        <div class="form-group" style="margin-top: 8px;">
            <?php echo Html::label('Невидимый', 'edit_hide', array('class' => 'col-xs-6 control-label')); ?>
            <div class="col-xs-6">
                <label class="checkbox">
                    <?php echo Html::checkBox('edit_hide', '', array('class' => 'checkbox form-control')); ?>
                    <span></span>
                </label>
            </div>
        </div>

        <div class="form-group">
            <div class="field col-xs-6 pull-right" id="edit_menu_button">
                <a href="javascript:;" onClick="saveMenu();" class="btn btn-primary col-xs-12">Сохранить</a>
            </div>
        </div>
    </div>
         </div>
</div>
<div class="clearfix"></div>