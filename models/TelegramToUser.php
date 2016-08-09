<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "telegram_to_user".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $telegram_id
 */
class TelegramToUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'telegram_to_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['user_id'], 'unique'],
            [['telegram_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'telegram_id' => 'Telegram ID',
        ];
    }
}
