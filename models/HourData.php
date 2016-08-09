<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "HourData".
 *
 * @property integer $id
 * @property integer $all_id
 * @property integer $branch_id
 * @property integer $month
 * @property integer $day
 * @property integer $year
 * @property integer $hour_n
 * @property integer $minutes_n
 * @property integer $seconds_n
 * @property integer $hour_k
 * @property integer $minutes_k
 * @property integer $seconds_k
 * @property double $paverage
 * @property double $taverage
 * @property double $pdelta
 * @property double $v_rc
 * @property double $v_sc
 * @property string $emergency
 */
class HourData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'HourData';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['all_id', 'branch_id', 'month', 'day', 'year', 'hour_n', 'minutes_n', 'seconds_n', 'hour_k', 'minutes_k', 'seconds_k'], 'integer'],
            [['paverage', 'taverage', 'pdelta', 'v_rc', 'v_sc'], 'number'],
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
            'hour_n' => 'Hour N',
            'minutes_n' => 'Minutes N',
            'seconds_n' => 'Seconds N',
            'hour_k' => 'Hour K',
            'minutes_k' => 'Minutes K',
            'seconds_k' => 'Seconds K',
            'paverage' => 'Paverage',
            'taverage' => 'Taverage',
            'pdelta' => 'Pdelta',
            'v_rc' => 'V Rc',
            'v_sc' => 'V Sc',
            'emergency' => 'Emergency',
        ];
    }
}
