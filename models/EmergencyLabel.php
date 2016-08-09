<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "emergency_label".
 *
 * @property integer $id
 * @property string $emergency_id
 * @property string $name
 */
class EmergencyLabel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'emergency_label';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['emergency_id', 'name'], 'required'],
            [['name'], 'string'],
            [['emergency_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'emergency_id' => 'Emergency ID',
            'name' => 'Name',
        ];
    }
}
