<?php

namespace app\modules\metrix\models;

use Yii;

/**
 * This is the model class for table "metrix_valve_operations".
 *
 * @property integer $id
 * @property string $counter_id
 * @property string $valve_status
 * @property string $created_at
 */
class MetrixValveOperation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'metrix_valve_operations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['counter_id'], 'integer'],
            [['valve_status'], 'string'],
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
            'counter_id' => 'Metrix ID',
            'valve_status' => 'Valve Status',
            'created_at' => 'Created At',
        ];
    }
}
