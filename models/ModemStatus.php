<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "modem_status".
 *
 * @property integer $id
 * @property integer $modem_id
 * @property integer $balance
 * @property string $invoice
 * @property string $time_on_line
 * @property string $status
 */
class ModemStatus extends \yii\db\ActiveRecord
{

    const MODEM_STATUS_ON_LINE = 'On-Line';
    const MODEM_STATUS_BUSY = 'Busy';
    const MODEM_STATUS_SLEEP = 'Sleep';
    const MODEM_STATUS_DISCONNECT = 'Disconnect';


    public static function getModemStatusList()
    {
        return [
            self::MODEM_STATUS_ON_LINE =>Yii::t('prom','Online'),// 'На связи',
            self::MODEM_STATUS_SLEEP => Yii::t('prom','Sleep'),//'В ожидании',
            self::MODEM_STATUS_BUSY =>Yii::t('prom','Busy'),// 'Занят',
            self::MODEM_STATUS_DISCONNECT =>Yii::t('prom','Disconnect'),// 'Нет связи',

        ];
    }

    public function getModemStatusText()
    {
        $modemStatusList = self::getModemStatusList();
        if (isset($modemStatusList[$this->status])) {
            return $modemStatusList[$this->status];
        } else {
            return $this->status;
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'modem_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'modem_id', 'balance'], 'integer'],
            [['invoice', 'status'], 'string'],
            [['time_on_line'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => Yii::t('metrix','phone'),
            'signal_level' => Yii::t('metrix','signal_level'),
            'modem_id' => Yii::t('metrix','modem_number'),
            'balance' => Yii::t('metrix','balance'),
            'invoice' => Yii::t('metrix','invoice'),
            'time_on_line' => Yii::t('metrix','time_on_line'),
            'status' => Yii::t('metrix','status'),
            'modemStatusText' => Yii::t('metrix','status'),
        ];
    }

    public function getGeo_location_id()
    {

        $cc = $this->hasOne(CorrectorToCounter::className(), array('modem_id' => 'modem_id'))->one();
        if (!empty($cc)) {
            return $cc->geo_location_id;
        } else {
            return '';
        }

    }

    public function getCorrector()
    {

        $cc=$this->hasOne(CorrectorToCounter::className(), array('modem_id' => 'modem_id'));

        if(!empty($cc)){
            return $cc;
        }else{
            return false;
        }


    }

    public function getType()
    {

        return 'gas';

    }

    public function getAddress()
    {

        return $this->hasOne(Address::className(), array('id' => 'geo_location_id'));

    }

}
