<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "alerts_list".
 *
 * @property integer $id
 * @property string $serial_number
 * @property string $type
 * @property string $created_at
 * @property string $status
 */
class AlertsList extends \yii\db\ActiveRecord
{

    
    const ALERT_TYPE_LEAK = 'leak';
    const ALERT_TYPE_MAGNET = 'magnet';
    const ALERT_TYPE_TAMPER = 'tamper';
    const ALERT_TYPE_LOWBATTERYLEVEL = 'lowBatteryLevel';
    const ALERT_TYPE_DISCONNECT = 'disconnect';
    const ALERT_TYPE_LOWBALANCE = 'lowBalance';
    
    const ALERT_DEVICE_TYPE_COUNTER = 'counter';
    const ALERT_DEVICE_TYPE_MODEM = 'modem';

    
    
    public static function getAlertTypeList(){
        return [
          
            self::ALERT_TYPE_LEAK => Yii::t('alerts','Leak'),//'Утечка',
            self::ALERT_TYPE_MAGNET => Yii::t('alerts','Magnet'),//'Магнит',
            self::ALERT_TYPE_TAMPER => Yii::t('alerts','Tamper'),//'Взлом',
            self::ALERT_TYPE_LOWBATTERYLEVEL => Yii::t('alerts','LowBatteryLevel'),//'Разряд батареи',
            self::ALERT_TYPE_DISCONNECT => Yii::t('alerts','Disconnect'),//'Потеря связи',
            self::ALERT_TYPE_LOWBALANCE=> Yii::t('alerts','LowBalance'),//'Недостаточно средсв на счету',

        ];
    }

    public function getAlertTypeText(){
        $typeList = self::getAlertTypeList();
        if(isset($typeList[$this->type])){
            return $typeList[$this->type];
        }else {
            return $this->type;
        }
    }
    
     public static function getAlertDeviceTypeList(){
        return [
          
            self::ALERT_DEVICE_TYPE_COUNTER => Yii::t('alerts','Counter'),//'счетчик',
            self::ALERT_DEVICE_TYPE_MODEM => Yii::t('alerts','Modem'),//'модем'
     
        ];
    }
    
    public function getAlertDeviceTypeText(){
        $typeList = self::getAlertDeviceTypeList();
        if(isset($typeList[$this->device_type])){
            return $typeList[$this->device_type];
        }else {
            return $this->device_type;
        }
    }
    
        /**
     * @inheritdoc
     */
    
    
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_DEACTIVATED = 'DEACTIVATED';
    const STATUS_WAITING_EMAIL_APPROVE = 'WAITING';
    const STATUS_DELETED = 'INWORK';
    
    
    const ROLE_USER = '';
    
    
    /**
     * 
     * Get user rolres
     * @author Igor
     */
    public static function getAllStatuses() {
        return [
            self::STATUS_ACTIVE =>Yii::t('alerts','Active'),//'Активен',
            self::STATUS_DEACTIVATED =>Yii::t('alerts','Deactivated'),//'Выполнен',
            self::STATUS_WAITING_EMAIL_APPROVE =>Yii::t('alerts','Waiting'),//'В Ожидании',
            self::STATUS_DELETED => Yii::t('alerts','InWork'),//'В Работе',
            
        ];
    }
    
    /**
     * 
     * Get user status human name
     * @return string|false
     * @author Igor
     */
    public function getStatusName() {
        $statuses = self::getAllStatuses();

        if(isset($statuses[$this->status])) 
            return $statuses[$this->status];
        else 
            return false;
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alerts_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {       
        $serialNumberModel='';
        $field='';
        if($this->device_type=='prom'){

                $serialNumberModel=ModemStatus::className();
               $field='modem_id';

            }
            else{

                $serialNumberModel=Modem::className();
                $field='serial_number';

            }

        
        return [
            ['serial_number', 'exist','targetClass'=>$serialNumberModel , 'targetAttribute' => $field],
            ['type','string'],
            ['device_type','in', 'range'=>['combit','counter','modem','prom']],
            ['status','in', 'range' => ['ACTIVE', 'DEACTIVATED', 'WAITING', 'INWORK']],
            [['serial_number','type','device_type'], 'required'],
            [['created_at'], 'safe']
        ];
    }
    public function getCounter(){
       // return $this->hasOne(Counter::className(), array('modem_id' => 'serial_number'));
        return $this->getItem();
    }
    public function getCounters(){
        return $this->hasOne(Counter::className(), array('modem_id' => 'serial_number'));

    }
    
       public function getItem(){
            $serialNumberModel='';
            $field='';
            if($this->device_type=='prom'){

            $serialNumberModel=ModemStatus::className();
            $field='modem_id';

            }
            else{

                $serialNumberModel=Modem::className();
                $field='serial_number';

            }



        return $this->hasOne($serialNumberModel,[$field=> 'serial_number']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address' =>Yii::t('alerts','address'),// '№ модема',
            'cause' =>Yii::t('alerts','cause'),// '№ модема',
            'serial_number' =>Yii::t('alerts','modemNumber'),// '№ модема',
            'type' => Yii::t('alerts','type'),//'Тип',
            'created_at' => Yii::t('alerts','time'),//'Дата и время',
            'device_type'=>Yii::t('alerts','deviceType'),//'Тип оборудования',
            'status' => Yii::t('alerts','status'),//'Состояние',
        ];
    }
}
