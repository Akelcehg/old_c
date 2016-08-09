<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "event_log".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $type
 * @property integer $region_id
 * @property string $counter_type
 * @property string $description
 * @property string $created_at
 */
class EventLog extends \yii\db\ActiveRecord
{
    const EVENT_TYPE_EDIT = 'edit';
    const EVENT_TYPE_CORRECTION = 'correction';
    const EVENT_TYPE_ALERT = 'alert';
    const EVENT_TYPE_LOGIN = 'login';
    const EVENT_TYPE_LOGOUT = 'logout';

    
    public static function getEventTypeList(){
        return [
          
            self::EVENT_TYPE_EDIT => Yii::t('events','Edit'),//'Редактирование',
            self::EVENT_TYPE_CORRECTION =>Yii::t('events','Correction'),// 'Коррекция',
            self::EVENT_TYPE_ALERT =>Yii::t('events','Alert'),// 'Предупреждение',
            self::EVENT_TYPE_LOGIN => Yii::t('events','Login'),//'Вход в систему',
            self::EVENT_TYPE_LOGOUT => Yii::t('events','Logout'),//'Выход из системы',
            
        ];
    }

    public function getEventTypeText(){
        $typeList = self::getEventTypeList();
        if(isset($typeList[$this->type])){
            return $typeList[$this->type];
        }else {
            return $this->type;
        }
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id','region_id'], 'integer'],
            [['counter_type','type', 'description'], 'string'],
            [['created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('events','Id'),//'ID',
            'user_id' => Yii::t('events','User'),//'Юзер',
            'type' =>  Yii::t('events','Type'),//'Тип',
            'counter_type' => Yii::t('events','CounterType'),//'Тип счётчика',
            'region_id' => Yii::t('events','Region'),//'Регион',
            'description' => Yii::t('events','Desctription'),//'Пояснение',
            'created_at' => Yii::t('events','Created_at'),//'Дата и Время',
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
