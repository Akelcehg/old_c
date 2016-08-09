<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "DateTime".
 *
 * @property integer $all_id
 * @property string $date
 * @property string $time
 */
class DateTime extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'DateTime';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['all_id'], 'integer'],
            [['date', 'time'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'all_id' => 'All ID',
            'date' => 'Date',
            'time' => 'Time',
        ];
    }
}
