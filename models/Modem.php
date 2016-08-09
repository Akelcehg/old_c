<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "modems".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $serial_number
 * @property string $phone_number
 * @property string $last_invoice_request
 * @property string $invoice_request
 * @property integer $update_interval
 * @property integer $signal_level
 * @property double $balans
 * @property double $last_temp
 * @property integer $geo_location_id
 * @property string $type
 * @property integer $everyday_update_interval
 * @property string $alert_datacode1
 * @property string $alert_datacode2
 * @property string $alert_datacode3
 * @property string $alert_datacode4
 * @property integer $alert_type1
 * @property integer $alert_type2
 * @property integer $alert_type3
 * @property integer $alert_type4
 * @property string $updated_at
 * @property string $created_at
 */
class Modem extends \yii\db\ActiveRecord
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
        return 'modems';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
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
            'user_id' => Yii::t('counter','user'),//'Пользователь',
            'serial_number' => Yii::t('counter','modem_number'),//'№ модема',
            'phone_number' => Yii::t('counter','phone_number'),//'Телефон',
            'last_invoice_request' => Yii::t('counter','balance'),// 'Баланс',
            'invoice_request' => Yii::t('counter','invoice_request'),//'Код запроса ',
            'signal_level' => Yii::t('counter','signal_level'),//'Уровень сигнала',
            'geo_location_id'=>Yii::t('counter','address'),//'Адресс',
            'updated_at' => Yii::t('counter','updated_at'),//'Данные обновлены ',
            'created_at' => Yii::t('counter','created_at'),//'Установлен ',
            'type' => Yii::t('counter','modem_type'),//'Тип модема',
            'everyday_update_interval'=>Yii::t('counter','everyday_update_interval'),//'Обязательное ежедневное обновление',
            'alert_datacode1' => 'Alert Datacode1',
            'alert_datacode2' => 'Alert Datacode2',
            'alert_datacode3' => 'Alert Datacode3',
            'alert_datacode4' => 'Alert Datacode4',
            'alert_type1' => 'Alert Type1',
            'alert_type2' => 'Alert Type2',
            'alert_type3' => 'Alert Type3',
            'alert_type4' => 'Alert Type4',
            'address'=>Yii::t('counter','address'),
            'battery_level'=>Yii::t('counter','battery_level')

        ];


    }

    public function getCounters(){
        return $this->hasMany(Counter::className(), array('modem_id' => 'serial_number'));
    }

    public function getRmodules(){
        return $this->hasMany(Rmodule::className(), array('modem_id' => 'serial_number'));
    }

    public function getAddress() {
        return $this->hasOne(Address::className(), array('id' => 'geo_location_id'));

    }

    public function getAlerts() {
        return $this->hasMany(AlertsList::className(), array('serial_number' => 'serial_number'));
    }




      public function getSimCard() {


        $card=SimCard::find()->where(['modem_id'=>$this->serial_number])->one();


          if(empty($card)){

              $card= new SimCard();
              $card->modem_id=$this->serial_number;
              $card->save();

              return $card;
          }else{
              return $card;
          }
    }

    public function getBalanceHistory() {


        return $this->hasMany(BalanceHistory::className(), array('modem_id' => 'serial_number'));
    }


    function  isForcedPayment(){

        return $this->isRequestInQueue($this->simCard->request_forced_payment);

    }

    function  isGetpacket(){

        return $this->isRequestInQueue($this->simCard->request_get_packet);

    }

    function  isRequestInQueue($command){

        $command=ModemDCommandConveyor::find()
            ->where(['modem_ID'=>$this->serial_number,'command'=>$command])
            ->andWhere(['or',['status'=>'ACTIVE'],['status'=>'PENDING']])
            ->one();

        if($command){
            return true;
        }else{
            return false;
        }

    }
}
