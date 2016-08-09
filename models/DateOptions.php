<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "DateOptions".
 *
 * @property integer $id
 * @property integer $all_id
 * @property integer $operative_interval
 * @property integer $cycle_time
 * @property integer $contract_hour
 * @property string $created_at
 */
class DateOptions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'DateOptions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'all_id', 'operative_interval', 'cycle_time', 'contract_hour'], 'integer'],
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
            'all_id' => 'All ID',
            'operative_interval' => 'Operative Interval',
            'cycle_time' => 'Cycle Time',
            'contract_hour' => 'Contract Hour',
            'created_at' => 'Created At',
        ];
    }
}
