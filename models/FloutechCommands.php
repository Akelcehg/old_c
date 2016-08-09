<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "floutech_commands".
 *
 * @property integer $id
 * @property string $command
 * @property string $description
 * @property string $created_at
 * @property integer $variables_count
 */
class FloutechCommands extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'floutech_commands';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'variables_count'], 'required'],
            [['id', 'variables_count'], 'integer'],
            [['created_at'], 'safe'],
            [['command', 'description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'command' => 'Command',
            'description' => 'Description',
            'created_at' => 'Created At',
            'variables_count' => 'Variables Count',
        ];
    }
    public function getVariables() {

        return $this->hasMany(FloutechVariables::className(), array('command_id' => 'id'))->all();
    }
}
