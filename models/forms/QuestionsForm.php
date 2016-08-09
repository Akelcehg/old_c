<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of QuestionsForm
 *
 * @property array $attributes
 *
 * @author dungpv
 */
class QuestionsForm extends CFormModel {
    public $excel;
    public $detectMode;
    public $sheet;
    
    public $detectModesArr = array(
        '1' => 'First answer',
        '2' => 'Text color',
        '3' => 'Background color'
    );
    
    public function rules() {
        return array(
            array('excel', 'file', 'types'=>'xls'),
            array('excel', 'safe', 'on'=>'excel'),
            array('sheet, detectMode', 'required'),
            array('sheet', 'numerical'),
        );
    }
    
    	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
            return array(
                'excel' => 'Excel file',
                'detectMode' => 'Correct answer detect mode',
                'sheet' => 'Sheet index',
            );
	}
}

?>
