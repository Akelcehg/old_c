<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "diagnostic".
 *
 * @property string $id
 * @property integer $all_id
 * @property integer $branch_id
 * @property string $timestamp
 * @property integer $month
 * @property integer $day
 * @property integer $year
 * @property integer $hour
 * @property integer $minutes
 * @property integer $seconds
 * @property string $params
 * @property double $vsc
 * @property string $created_at
 */
class Diagnostic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Diagnostic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['all_id', 'branch_id', 'month', 'day', 'year', 'hour', 'minutes', 'seconds'], 'integer'],
            [['timestamp', 'created_at'], 'safe'],
            [['vsc'], 'number'],
            [['params'], 'string', 'max' => 255]
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
            'branch_id' => 'Branch ID',
            'timestamp' => 'Timestamp',
            'month' => 'Month',
            'day' => 'Day',
            'year' => 'Year',
            'hour' => 'Hour',
            'minutes' => 'Minutes',
            'seconds' => 'Seconds',
            'params' => 'Params',
            'vsc' => 'Vsc',
            'created_at' => 'Created At',
        ];
    }
}
