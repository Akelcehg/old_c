<?php

namespace app\modules\metrix\models;

use Yii;

/**
 * This is the model class for table "metrix_command_ask_answer".
 *
 * @property integer $id
 * @property integer $modem_id
 * @property integer $command_conveyor_id
 * @property integer $corrector_id
 * @property integer $branch_id
 * @property string $ask
 * @property string $answer
 * @property string $command
 * @property string $created_at
 * @property string $answered_at
 */
class MetrixCommandAskAnswer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'metrix_command_ask_answer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['modem_id', 'command_conveyor_id', 'corrector_id', 'branch_id'], 'integer'],
            [['created_at', 'answered_at'], 'safe'],
            [['ask', 'answer', 'command'], 'string', 'max' => 255]
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
            'ask' => 'Ask',
            'answer' => 'Answer',
            'command' => 'Command',
            'created_at' => 'Created At',
            'answered_at' => 'Answered At',
        ];
    }
}
