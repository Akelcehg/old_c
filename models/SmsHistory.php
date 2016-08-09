<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sms_history".
 *
 * @property integer $id
 * @property integer $modem_id
 * @property string $sms
 * @property string $date
 */
class SmsHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           // [['id'], 'required'],
            [['id', 'modem_id'], 'integer'],
            [['sms'], 'string'],
            [['date'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'modem_id' => 'Modem ID',
            'sms' => 'Sms',
            'date' => 'Date',
        ];
    }
}
