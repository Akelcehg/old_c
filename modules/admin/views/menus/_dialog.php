<?php
use yii\jui\Dialog;

Dialog::begin(array(
    'id' => 'dialogAddEditMenu',
    'options' => array(
        'title' => 'Add/Edit Menu',
        'autoOpen' => false,
        'modal' => true,
        'width' => 450,
        'height' => 400,
        'hide' => 'explode',
        'show' => 'explode',
        'open' => "js:function(){
            jQuery('.ui-widget-overlay').bind('click',function(){
                jQuery('#dialogAddEditMenu').dialog('close');
            })}
        ",
        //'buttons' => array(
         //       'Create' => 'js:function(){$("div.divForForm form").submit();}',
         ///       'Cancel' => 'js:function(){$(this).dialog("close");}',
          //  )
    ),
));

echo '<div class="divForForm"></div>';

Dialog::end();

?>