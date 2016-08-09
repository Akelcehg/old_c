<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "modemD_command_conveyor".
 *
 * @property integer $id
 * @property integer $modem_id
 * @property string $command
 * @property integer $command_type
 * @property string $status
 * @property string $created_at
 * @property string $pending_at
 * @property string $disabled_at
 */
class ModemDCommandConveyor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'modemD_command_conveyor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['modem_id', 'command_type'], 'integer'],
            [['status'], 'string'],
            [['created_at', 'pending_at', 'disabled_at'], 'safe'],
            [['command'], 'string', 'max' => 255]
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
            'command' => 'Command',
            'command_type' => 'Command Type',
            'status' => 'Status',
            'created_at' => 'Created At',
            'pending_at' => 'Pending At',
            'disabled_at' => 'Disabled At',
        ];
    }
}
