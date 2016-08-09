<?php

/*
 */
class DashboardManagePageSize extends CWidget {
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
    public $customOnChange = '';

    public $gridId = '';

    public $options = '';

    public function init() {
        parent::init();
    }

    public function getList() {
        ob_start();
        if ($this->allowLabel)
            echo CHtml::label($this->label, $this->dropDownListId,array('class'=>$this->labelClass));
        $htmlOptions = array('class'=>$this->class);

        $htmlOptions['options'] = !empty($this->options) ? $this->options : '';

        if ($this->addOnChange)
            $htmlOptions['onChange'] = !empty($this->customOnChange) ? $this->customOnChange : "$.fn.yiiGridView.update('{$this->gridId}', { url: '" . Yii::app()->request->url . "', data:{ pageSize: $(this).val() }})";

        echo CHtml::dropDownList($this->dropDownListId, Yii::app()->user->getState('pageSize', Yii::app()->params['defaultPageSize']), $this->size, $htmlOptions);

        $contents = ob_get_clean();

        return $contents;
    }
}