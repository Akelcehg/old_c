<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "documents".
 *
 * @property integer $id
 * @property integer $address_id
 * @property integer $counter_model_id
 * @property string $description
 * @property string $status
 * @property string $how_hard
 * @property string $type
 * @property integer $user_id
 * @property string $created_at
 */
class Documents extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documents';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address_id', 'counter_model_id', 'user_id'], 'integer'],
            [['description', 'status', 'how_hard', 'type'], 'string'],
            [['address_id', 'counter_model_id'], 'required'],
            [['address_id', 'counter_model_id'], 'greaterThanZero'],
            [['created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address_id' => 'Адрес',
            'counter_model_id' => 'Модель счётчиика',
            'description' => 'Описание',
            'status' => 'Статус',
            'how_hard' => 'Сложность монтажа',
            'type' => 'Тип акта',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
    }

    public function getAddress() {
        return $this->hasOne(Address::className(), array('id' => 'address_id'));
    }

    public function getFulladdress() {
        $model=$this->hasOne(Address::className(), array('id' => 'address_id'))->one();

        if(isset($model->street)){
            return $model->fulladdress;}
    }

    public function getCounterModel() {
        return $this->hasOne(CounterModel::className(), array('id' => 'counter_model_id'));
    }

    public function greaterThanZero($attribute, $params)
    {

        if ($this->$attribute <= 0)
            $this->addError($attribute, 'Выберите вариант из выпадающего списка');

    }
}
