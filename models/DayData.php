<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "DayData".
 *
 * @property integer $id
 * @property integer $all_id
 * @property integer $branch_id
 * @property integer $month
 * @property integer $day
 * @property integer $year
 * @property integer $hour
 * @property integer $minutes
 * @property integer $seconds
 * @property integer $time_emerg
 * @property integer $time_emerg2
 * @property double $paverage
 * @property double $taverage
 * @property double $pdelta
 * @property double $v_rc
 * @property double $v_sc
 * @property double $vpokaz_rc
 * @property string $emergency
 * @property string $created_at
 */
class DayData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'DayData';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['all_id', 'branch_id', 'month', 'day', 'year', 'hour', 'minutes', 'seconds', 'time_emerg', 'time_emerg2'], 'integer'],
            [['paverage', 'taverage', 'pdelta', 'v_rc', 'v_sc', 'vpokaz_rc'], 'number'],
            [['created_at'], 'safe'],
            [['emergency'], 'string', 'max' => 255]
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
            'month' => 'Month',
            'day' => 'Day',
            'year' => 'Year',
            'hour' => 'Hour',
            'minutes' => 'Minutes',
            'seconds' => 'Seconds',
            'time_emerg' => 'Time Emerg',
            'time_emerg2' => 'Time Emerg2',
            'paverage' => 'Paverage',
            'taverage' => 'Taverage',
            'pdelta' => 'Pdelta',
            'v_rc' => 'V Rc',
            'v_sc' => 'V Sc',
            'vpokaz_rc' => 'Vpokaz Rc',
            'emergency' => 'Emergency',
            'created_at' => 'Created At',
        ];
    }
}
