<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Intervention".
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
 * @property double $params
 * @property double $old_params
 * @property double $new_params
 * @property string $created_at
 */
class Intervention extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Intervention';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['all_id', 'branch_id', 'month', 'day', 'year', 'hour', 'minutes', 'seconds'], 'integer'],
            [['params', 'old_params', 'new_params'], 'number'],
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
            'branch_id' => 'Branch ID',
            'month' => 'Month',
            'day' => 'Day',
            'year' => 'Year',
            'hour' => 'Hour',
            'minutes' => 'Minutes',
            'seconds' => 'Seconds',
            'params' => 'Params',
            'old_params' => 'Old Params',
            'new_params' => 'New Params',
            'created_at' => 'Created At',
        ];
    }
}
