<?php

namespace app\components\managePageSize;
use yii\base\Widget;
use yii\helpers\Html;
use Yii;


/*
 */
class ManagePageSize extends Widget {
    public $size = array('25' => '25', '50' => '50', '75' => '75', '100' => '100');

    public $dropDownListId = 'pageSize';

    public $label = 'Results per page: ';

    public $allowLabel = true;
    public $labelClass = '';
    public $class = '';

    /**
     * Add scripts, that update grid when the PageSize select is changed the value
     * @var bool
     */
    public $addOnChange = true;

    public $gridId = '';

    public function init() {
        parent::init();
    }

    public function getList() {
        ob_start();
        if ($this->allowLabel)
            echo Html::label($this->label, $this->dropDownListId,array('class'=>$this->labelClass));
        $htmlOptions = array('class'=>$this->class);
        if ($this->addOnChange)
            $htmlOptions['onChange'] = "$.fn.yiiGridView.update('{$this->gridId}', { data:{ pageSize: $(this).val() }})";
        echo Html::dropDownList($this->dropDownListId, Yii::$app->session->get('pageSize', Yii::$app->params['defaultPageSize']), $this->size, $htmlOptions);
        $contents = ob_get_clean();

        return $contents;
    }
}
