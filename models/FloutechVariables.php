<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "floutech_variables".
 *
 * @property integer $id
 * @property integer $command_id
 * @property integer $len
 * @property integer $order
 * @property string $description
 * @property string $created_at
 */
class FloutechVariables extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'floutech_variables';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['command_id', 'len', 'order', 'description'], 'required'],
            [['command_id', 'len', 'order'], 'integer'],
            [['created_at'], 'safe'],
            [['description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'command_id' => 'Command ID',
            'len' => 'Len',
            'order' => 'Order',
            'description' => 'Description',
            'created_at' => 'Created At',
        ];
    }
}
