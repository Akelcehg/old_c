<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_indications".
 *
 * @property integer $id
 * @property integer $user_counter_id
 * @property integer $indications
 * @property integer $impuls
 * @property string $created_at
 */
class UserIndications extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_indications';
    }

    /**
     * Get User Counter
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserCounter()
    {
        return $this->hasOne(UserCounters::className(), ['serial_number' => 'user_counter_id']);
    }
    
     public function getCounter()
    {
        return $this->hasOne(UserCounters::className(), ['serial_number' => 'user_counter_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_counter_id', 'indications', 'impuls'], 'required'],
            [['user_counter_id', 'impuls'], 'integer'],
            ['indications','double'],
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
            'user_counter_id' => '№ Радиомодуля',
            'indications' => 'Показания',
            'impuls'=>'Импульсы',
            'created_at' => 'Дата и Время',
        ];
    }
}
