<?php

use yii\jui\Dialog;
use yii\helpers\Html;

/*Dialog::begin(array(
        'id'=>'dialogCloseUser',
        // additional javascript options for the dialog plugin
        'options'=>array(
            'title'=>'Close Account Confirm',
            'autoOpen'=>false,
            'modal' => true,
            'minWidth' => 410,
            'buttons' => array(
                array(
                    'text' => 'Close Account',
                    'click' => 'js:function(){ closeUser(); return false;}',
                    'class' => 'btn btn-primary'
                ),array(
                    'text' => 'Cancel',
                    'click' => 'js:function(){ $(this).dialog("close"); }',
                    'class' => 'btn btn-default'
                )
            )
        ),
    ));*/
?>
<div id="dialogCloseUserContent">
    <div id="dialogCloseUserQuestion">
        Reason for closure
    </div>
    <div id="dialogCloseUserAnswer" class="smart-form">
        <?php // echo Html::dropDownList('reasonsList', '', ClosedUser::model()->REASONS_TO_CLOSE,array('onchange'=>'setCloseUserReason()','class'=>'form-control','style'=>'width:300px;height:auto;')); ?>
        <br/>
        <hr/>
        <br/>
        <label id="dialogCloseUserCheckBox" class="checkbox">
            <?php echo Html::checkBox('reasonToCloseCheckBox', false,array('onchange'=>'showHideReasonToCloseInput()','class'=>'checkbox style-0')); ?>
            <i></i>
            <?php echo Html::label('Other Reason','reasonToCloseCheckBox',array('class'=>'label-control')); ?>
        </label>

        <label class="input">
            <?php echo Html::textInput('reasonToCloseInput', '',array('style'=>'display:none;','maxlength'=>'50')); ?>
        </label>
        <div id="dialogCloseUserErrorMessage">
        </div>
    </div>
</div>


<?php /*Dialog::end();*/ ?>


<script type="text/javascript">

    function stripExistingScripts(html)
    {
        var map = {
            "jquery.js": "$",
            "jquery.min.js": "$",
            "jquery-ui.min.js": "$.ui",
            "jquery.yiiactiveform.js": "$.fn.yiiactiveform",
            "jquery.yiigridview.js": "$.fn.yiiGridView",
            "jquery.ba-bbq.js": "$.bbq"
        };

        for (var scriptName in map) {
            var target = map[scriptName];
            if (isDefined(target)) {
                var regexp = new RegExp('<script.*src=".*' +
                scriptName.replace('.', '\\.') +
                '".*<' + '/script>', 'i');
                html = html.replace(regexp, '');
            }
        }

        return html;
    }

    function isDefined(path)
    {
        var target = window;
        var parts = path.split('.');

        while(parts.length) {
            var branch = parts.shift();
            if (typeof target[branch] === 'undefined') {
                return false;
            }
            target = target[branch];
        }

        return true;
    }
</script>

