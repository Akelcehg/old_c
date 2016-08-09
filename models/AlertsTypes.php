<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "alerts_types".
 *
 * @property integer $id
 * @property string $name
 */
class AlertsTypes extends \yii\db\ActiveRecord
{
    
     const ALERT_TYPE_LEAK = 'leak';
    const ALERT_TYPE_MAGNET = 'magnet';
    const ALERT_TYPE_TAMPER = 'tamper';
    const ALERT_TYPE_LOWBATTERYLEVEL = 'lowBatteryLevel';
    const ALERT_TYPE_DISCONNECT = 'disconnect';
    const ALERT_TYPE_LOWBALANCE = 'lowBalance';
    
    public static function getAlertTypeList(){
        return [
          
            self::ALERT_TYPE_LEAK => Yii::t('alerts','Leak'),//'Утечка',
            self::ALERT_TYPE_MAGNET =>Yii::t('alerts','Magnet'),// 'Магнит',
            self::ALERT_TYPE_TAMPER => Yii::t('alerts','Tamper'),//'Взлом',
            self::ALERT_TYPE_LOWBATTERYLEVEL =>  Yii::t('alerts','LowBatteryLevel'),//'Разряд батареи',
            self::ALERT_TYPE_DISCONNECT =>  Yii::t('alerts','Disconnect'),//'Потеря связи',
            self::ALERT_TYPE_LOWBALANCE=> Yii::t('alerts','LowBalance'),//'Недостаточно средств на счету',
            
        ];
    }

    public function getAlertTypeText(){
        $typeList = self::getAlertTypeList();
        if(isset($typeList[$this->name])){
            return $typeList[$this->name];
        }else {
            return $this->name;
        }
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alerts_types';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
        ];
    }
}
