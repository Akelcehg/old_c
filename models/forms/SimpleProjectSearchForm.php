<?php

/**
 * User: Yuri Datsenko
 * Date: 20.11.13
 * Time: 16:08
 */
class SimpleProjectSearchForm extends CFormModel {
    public $id;
    public $name;
    public $client_id;

    function rules() {
        return array(
            array('id, name, client_id', 'safe'),
        );
    }

    function attributeLabels() {
        return array(
            'id' => 'Project Id',
            'name' => 'Project Name'
        );
    }


    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        $criteria->compare('client_id', $this->client_id);
        //$criteria->compare('name', $this->name, true);
        if($this->name){
            $criteria->mergeWith(Project::getSearchCriteria($this->name));
        }

        return new CActiveDataProvider('Project', array(
            'criteria' => $criteria,
            'sort' => Project::model()->getSort(),
            'pagination' => array(
                'pageSize' => 10
            )
        ));
    }
}
