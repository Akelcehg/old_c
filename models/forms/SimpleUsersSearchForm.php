<?php
/**
 * @file: SimpleUsersSearchForm.php
 * @author: Yuri Datsenko
 * @date: 02.12.13 21:32
 */


class SimpleUsersSearchForm extends CFormModel {
    public $id;
    public $name;
    public $type;

    function __construct($type = null) {
        parent::__construct();
        $this->type = $type;
    }

    function rules() {
        return array(
            array('id, name', 'safe'),
        );
    }

    function attributeLabels() {
        return array(
            'id' => 'User Id',
            'name' => 'User Name'
        );
    }


    public function search() {
        $criteria = new CDbCriteria;
        if ($this->type == 'approved')
            $criteria->addCondition('t.status = "' . User::STATUS_APPROVED . '"');
        $criteria->compare('id', $this->id, true);
        if ($this->name)
            $criteria->mergeWith(User::getUsersSearchCriteria($this->name));

        return new CActiveDataProvider('User', array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 't.id',
                'attributes' => array(
                    '*',
                ),
            ),
            'pagination' => array(
                'pageSize' => 10
            )
        ));
    }
}