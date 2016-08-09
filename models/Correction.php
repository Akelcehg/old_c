<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "correction".
 *
 * @property integer $id
 * @property integer $counter_id
 * @property integer $old_indications_id
 * @property integer $new_indications_id
 * @property string $description
 */
class Correction extends \yii\db\ActiveRecord
{
    /**
     * Get User Counter
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserCounter()
    {
        return $this->hasOne(Counter::className(), ['id' => 'counter_id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'correction';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['counter_id', 'old_indications_id', 'new_indications_id'], 'required'],
            [['counter_id', 'old_indications_id', 'new_indications_id'], 'integer'],
            [['description'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'counter_id' => '№ Радиомодуля',
            'old_indications_id' => '№ предыдушего показания',
            'new_indications_id' => '№ Коррекции',
            'description' => 'Объяснение',
        ];
    }
}
