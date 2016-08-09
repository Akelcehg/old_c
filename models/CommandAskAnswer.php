<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "command_ask_answer".
 *
 * @property integer $id
 * @property integer $modem_id
 * @property integer $command_conveyor_id
 * @property integer $corrector_id
 * @property integer $branch_id
 * @property string $command
 * @property string $ask
 * @property string $answer
 * @property string $created_at
 * @property string $answered_at
 */
class CommandAskAnswer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'command_ask_answer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['modem_id', 'command_conveyor_id', 'corrector_id', 'branch_id'], 'integer'],
            [['branch_id', 'command'], 'required'],
            [['ask', 'answer'], 'string'],
            [['created_at', 'answered_at'], 'safe'],
            [['command'], 'string', 'max' => 2]
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
            'command_conveyor_id' => 'Command Conveyor ID',
            'corrector_id' => 'Corrector ID',
            'branch_id' => 'Branch ID',
            'command' => 'Command',
            'ask' => 'Ask',
            'answer' => 'Answer',
            'created_at' => 'Created At',
            'answered_at' => 'Answered At',
        ];
    }
}
