<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "traffic".
 *
 * @property string $id
 * @property integer $modem_id
 * @property integer $byte_count
 * @property string $type
 * @property string $created_at
 */
class Traffic extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'traffic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['modem_id', 'byte_count'], 'integer'],
            [['created_at'], 'safe'],
            [['type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'modem_id' => '№ Модема',
            'byte_count' => 'Byte Count',
            'type' => 'Type',
            'created_at' => 'Created At',
        ];
    }
}
