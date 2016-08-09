<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_modems".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $serial_number
 * @property string $phone_number
 * @property string $last_invoice_request
 * @property integer $geo_location_id
 * @property string $invoice_request
 * @property integer $signal_level
 * @property integer $update_interval
 * @property string $updated_at
 * @property string $created_at
 */
class UserModems extends \yii\db\ActiveRecord
{
    
    
   const MODEM_TYPE_BUILT_IN = 'built-in';
   const MODEM_TYPE_DISCRETE = 'discrete';
    
    
    public static function getModemTypeList(){
        return [
            self::MODEM_TYPE_BUILT_IN => 'ABV4',
            self::MODEM_TYPE_DISCRETE => 'ARV4',
            
        ];
    }

    public function getModemTypeText(){
        $modemTypeList = self::getModemTypeList();
        if(isset($modemTypeList[$this->type])){
            return $modemTypeList[$this->type];
        }else {
            return $this->type;
        }
    }
    

    
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_modems';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            /*[['user_id', 'signal_level', 'update_interval'], 'integer'],
            [['updated_at', 'created_at'], 'safe'],
            [['serial_number', 'last_invoice_request'], 'string', 'max' => 255],
            [['phone_number'], 'string', 'max' => 10],
            [['invoice_request'], 'string', 'max' => 32],*/
            
            
            [['user_id', 'signal_level'], 'integer','on'=>'editModem'],
            [[ 'geo_location_id','serial_number'], 'integer','on'=>'editModem'],
            [['updated_at', 'created_at'], 'safe','on'=>'editModem'],
         
            [['last_invoice_request'], 'string', 'max' => 255,'on'=>'editModem'],
            [['phone_number'], 'string', 'max' => 15,'on'=>'editModem'],
            [['invoice_request'], 'string', 'max' => 32,'on'=>'editModem'],
            [['everyday_update_interval'],'integer','max' => 24,'min'=>0,'on'=>'editModem']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'serial_number' => '№ модема',
            'phone_number' => 'Телефон',
            'last_invoice_request' => 'Баланс',
            'invoice_request' => 'Код запроса ',
            'signal_level' => 'Уровень сигнала',
            'geo_location_id'=>'Адресс',
            'updated_at' => 'Данные обновлены ',
            'created_at' => 'Установлен ',
            'type' => 'Тип модема',
            'everyday_update_interval'=>'Обязательное ежедневное обновление',
        ];
    }
    
    public function getCounters(){
        return $this->hasMany('app\models\UserCounters', array('user_modem_id' => 'serial_number'));
    }
    
    public function getAddress() {
        return $this->hasOne('app\models\CounterAddress', array('id' => 'geo_location_id'));
    }
    
 
    
    public function changeCountersAddress(){
        
        $counters=$this->counters;
        
        foreach ($counters as $counter){
            
            $counter->geo_location_id=$this->geo_location_id;
            $counter->save();
            
            }
        
    }
}
