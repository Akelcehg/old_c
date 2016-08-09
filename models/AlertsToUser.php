<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "alerts_to_user".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $alerts_type_id
 */
class AlertsToUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alerts_to_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'alerts_type_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Ф.И.О',
            'alerts_type_id' => 'Тип Предупреждений',
        ];
    }

    public function getType()
    {
        return $this->hasOne(AlertsTypes::className(),['id'=>'alerts_type_id']);
    }
}
