<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Regions".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'regions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'name'], 'required'],
            [['parent_id'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'name' => 'Name',
        ];
    }

    public function getFullRegionName()
    {

        $parent = $this->findOne(['id'=>$this->parent_id]);

        if($parent){
            return 'город '.$this->name.' '.$parent->name .' обл.';}
        else {
            return '';
        }
    }
}
