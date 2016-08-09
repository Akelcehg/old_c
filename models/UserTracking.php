<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users_tracking".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $time_in
 * @property string $time_out
 * @property string $created_at
 */
class UserTracking extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users_tracking';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['time_in', 'time_out', 'created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' =>Yii::t('events','User'),//'User ID',
            'user_action' =>Yii::t('events','UserAction'),//'User ID',
            'delay' =>Yii::t('events','Delay'),//'User ID',
            'time_in' => 'Time In',
            'time_out' => 'Time Out',
            'created_at' => 'Created At',
        ];
    }

    public function getUser() {

        if($user=$this->hasOne('app\models\User', array('id' => 'user_id')))
        {
            return $user;
        }else{
            return false;
        };

    }
}
