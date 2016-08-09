<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_modem_temparatues".
 *
 * @property integer $id
 * @property integer $user_modem_id
 * @property double $temp
 * @property string $created_at
 */
class ModemTemparatues extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'modem_temparatues';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['modem_id'], 'integer'],
            [['temp'], 'number'],
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
            'user_modem_id' => 'User Modem ID',
            'temp' => 'Temp',
            'created_at' => 'Created At',
        ];
    }
}
