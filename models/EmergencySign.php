<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "emergency_situation".
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
 * @property integer $hour_end
 * @property integer $minutes_end
 * @property integer $seconds_end
 * @property integer $count_p
 * @property string $params
 * @property double $var1
 * @property double $var2
 * @property double $var3
 * @property double $var4
 * @property double $empty1
 * @property double $empty2
 * @property integer $time
 * @property double $vsc
 * @property string $created_at
 */
class EmergencySign extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'emergency_sign';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['all_id', 'branch_id', 'month', 'day', 'year', 'hour', 'minutes', 'seconds', 'hour_end', 'minutes_end', 'seconds_end', 'count_p', 'time'], 'integer'],
            [['timestamp', 'created_at'], 'safe'],
            [['var1', 'var2', 'var3', 'var4', 'empty1', 'empty2', 'vsc'], 'number'],
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
            'hour_end' => 'Hour End',
            'minutes_end' => 'Minutes End',
            'seconds_end' => 'Seconds End',
            'count_p' => 'Count P',
            'params' => 'Params',
            'var1' => 'Var1',
            'var2' => 'Var2',
            'var3' => 'Var3',
            'var4' => 'Var4',
            'empty1' => 'Empty1',
            'vsc' => 'Vrc',
            'time' => 'Time',
            'vsc' => 'Vsc',
            'created_at' => 'Created At',
        ];
    }
}
