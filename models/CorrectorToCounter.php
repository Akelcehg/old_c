<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "corrector_to_counter".
 *
 * @property integer $id
 * @property integer $counter_id
 * @property integer $modem_id
 * @property integer $corrector_id
 * @property integer $branch_id
 * @property string $created_at
 * @property string $prog
 */
class CorrectorToCounter extends \yii\db\ActiveRecord
{


    const STATUS_ENABLED = 'ENABLED';
    const STATUS_DISABLED = 'DISABLED';

    public static function getAllStatuses() {
        return [

            self::STATUS_ENABLED =>'Включен',
            self::STATUS_DISABLED =>'Выключен',

        ];
    }

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
        return 'corrector_to_counter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['counter_id', 'corrector_id', 'branch_id'], 'integer'],
            [['update_interval'], 'integer','min'=>1,'max'=>1440],
            [['created_at','hw_status','prog'], 'safe']
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'counter_id' => 'Counter ID',
            'corrector_id' => 'Corrector ID',
            'hw_status'=>Yii::t('prom','hw_status'),//'Состояние',
            'company'=>Yii::t('prom','Company'),
            'contract'=>Yii::t('prom','Contract'),
            'geo_location_id'=>Yii::t('prom','Address'),
            'branch_id' => 'Branch ID',
            'created_at' => 'Created At',
            'prog'=>'ПО',
            'cycle'=>Yii::t('prom','Cycle'),
            'lastTrafficInSum'=>Yii::t('prom','lastTrafficInSum'),
            'lastTrafficOutSum'=>Yii::t('prom','lastTrafficOutSum'),
            'dayTrafficInSum'=>Yii::t('prom','dayTrafficInSum'),
            'dayTrafficOutSum'=>Yii::t('prom','dayTrafficOutSum'),
            'monthTrafficInSum'=>Yii::t('prom','monthTrafficInSum'),
            'monthTrafficOutSum'=>Yii::t('prom','monthTrafficOutSum'),
            'update_interval'=>Yii::t('metrix','update_interval'),
        ];
    }

    public function getAddress()
    {
        return $this->hasOne(Address::className(), array('id' => 'geo_location_id'));
    }

    public function getMomentData()
    {
        return $this->hasOne(MomentData::className(), array('all_id' => 'id'))->orderBy(['created_at' => SORT_DESC]);
    }

    public function getLastDayData()
    {
        return $this->hasMany(DayData::className(), array('all_id' => 'id'))->orderBy(['timestamp' => SORT_DESC])->one();
    }

    public function getLastMonthConsumption()
    {

        $dt = new \DateTime();
        $di = new \DateInterval("P1M");
        $di->invert = 1;
        $dt->add($di);

        $cons = $this->hasMany(DayData::className(), ['all_id' => 'id'])
            ->where('timestamp>:time1 AND timestamp<:time2', [":time1" => $dt->format("Y-m") . "-01 00:00:00", ":time2" => $dt->format("Y-m-t") . " 23:59:59",])
            ->sum("v_sc");

        $consAv = $this->hasMany(DayData::className(), ['all_id' => 'id'])
            ->where('timestamp>:time1 AND timestamp<:time2', [":time1" => $dt->format("Y-m") . "-01 00:00:00", ":time2" => $dt->format("Y-m-t") . " 23:59:59",])
            ->sum("vav_sc");

        return $cons + $consAv;
    }

      public function getDateOptions()
    {
        return $this->hasMany(DateOptions::className(), array('all_id' => 'id'))->orderBy(['id'=>SORT_DESC])->one();
    }


    public function getCurrentMonthConsumption()
    {

        $dt = new \DateTime();


        $cons = $this->hasMany(DayData::className(), ['all_id' => 'id'])
            ->where('timestamp>:time1 AND timestamp<:time2', [":time1" => $dt->format("Y-m") . "-01 00:00:00", ":time2" => $dt->format("Y-m-t") . " 23:59:59",])
            ->sum("v_sc");

        $consAv = $this->hasMany(DayData::className(), ['all_id' => 'id'])
            ->where('timestamp>:time1 AND timestamp<:time2', [":time1" => $dt->format("Y-m") . "-01 00:00:00", ":time2" => $dt->format("Y-m-t") . " 23:59:59",])
            ->sum("vav_sc");

        return $cons + $consAv;
    }

    public function getStatus()
    {
        return $this->hasOne(ModemStatus::className(), array('modem_id' => 'modem_id'));
    }

    public function getEmergencySituation()
    {
        return $this->hasMany(EmergencySituation::className(), array('all_id' => 'id'));
    }

    public function getEmergencySituationOnThisMonth()
    {
        if(isset($this->dateOptions)){
            return $this->getEmergencySituation()->where(['>', 'timestamp', date('Y-m-1') . " ".$this->dateOptions->contract_hour.":00:00"]);
        }else{
            return [];
        }


    }

    public function getEmergencySituationOnThisMonthCount()
    {
        if(!is_array($this->getEmergencySituationOnThisMonth())){
            return $this->getEmergencySituationOnThisMonth()->count();
        }else{
            return 0;
        }

    }

    public function getDiagnostic()
{
    return $this->hasMany(Diagnostic::className(), array('all_id' => 'id'));
}

    public function getDiagnosticOnThisMonth()
    {
        if(isset($this->dateOptions)){
        return $this->getDiagnostic()->where(['>', 'timestamp', date('Y-m-1') . " ".$this->dateOptions->contract_hour.":00:00"]);
        }else{
            return [];
        }
    }

    public function getDiagnosticOnThisMonthCount()
    {
        if(!is_array($this->getDiagnosticOnThisMonth())){
        return $this->getDiagnosticOnThisMonth()->count();
        }else{
            return 0;
        }

    }

    public function getIntervention()
    {
        return $this->hasMany(Intervention::className(), array('all_id' => 'id'));
    }

    public function getInterventionOnThisMonth()
    {if(isset($this->dateOptions)){
        return $this->getIntervention()->where(['>', 'timestamp', date('Y-m-1') . " ".$this->dateOptions->contract_hour.":00:00"]);
    }else{
        return [];
    }
    }

    public function getInterventionOnThisMonthCount()
    {
        if(!is_array($this->getInterventionOnThisMonth())){
        return $this->getInterventionOnThisMonth()->count();
        }else{
            return 0;
        }
    }

    public function getPromInfo()
    {

        $aaap = $this->hasOne(PromInfo::className(), array('id' => 'id'));
        if ($aaap) {

            return $aaap;
        } else {
            return '';
        }


    }

    public function getFirstDayReportDate()
    {
        $hd = HourData::find()->where(['all_id' => $this->id])->orderBy('timestamp')->one();
        if (!empty($hd)) {

            return $hd->timestamp;

        } else {
            return false;
        }
    }

    public function getFirstMonthReportDate()
    {
        $hd = DayData::find()->where(['hour' => 9, 'all_id' => $this->id])->orderBy('timestamp')->one();
        if (!empty($hd)) {

            return $hd->year . '-01-01';

        } else {
            return false;
        }
    }


    public function getSimCard()
    {


        $card = SimCard::find()->where(['modem_id' => $this->modem_id])->one();


        if (empty($card)) {

            $card = new SimCard();
            $card->modem_id = $this->modem_id;
            $card->save();

            return $card;
        } else {
            return $card;
        }
    }

    function isForcedPayment()
    {

        return $this->isRequestInQueue($this->simCard->request_forced_payment);

    }

    function isGetpacket()
    {

        return $this->isRequestInQueue($this->simCard->request_get_packet);

    }

    function isForcedReport()
    {

        return $this->isRequestInQueue("25");

    }

    function isForcedMomentData()
    {

        return $this->isRequestInQueue("04");

    }

    function isForcedHourData()
    {

        return $this->isRequestInQueue("25");

    }

    function isRequestInQueue($command)
    {

        if (!empty($command)) {
            $command = CommandConveyor::find()
                ->where(['modem_id' => $this->modem_id])
                ->andWhere(['like', 'command', $command])
                ->andWhere(['or', ['status' => 'ACTIVE'], ['status' => 'PENDING']])
                ->one();
        } else {
            return false;
        }

        if ($command) {
            return true;
        } else {
            return false;
        }

    }


    public function getTraffic()
    {
        return $this->hasMany(Traffic::className(), array('modem_id' => 'modem_id'));
    }




    public function getMonthTrafficIn()
    {
        return $this->hasMany(Traffic::className(), array('modem_id' => 'modem_id'))->andWhere(['type'=>'in'])->andWhere(['>','created_at',date('Y-m-d 00:00:00')])->andWhere(['<','created_at',date('Y-m-t 00:00:00')]);
    }

    public function getMonthTrafficOut()
    {
        return $this->hasMany(Traffic::className(), array('modem_id' => 'modem_id'))->andWhere(['type'=>'out'])->andWhere(['>','created_at',date('Y-m-d 00:00:00')])->andWhere(['<','created_at',date('Y-m-t 00:00:00')]);
    }

    public function getMonthTrafficInSum()
    {
        $count=$this->hasMany(Traffic::className(), array('modem_id' => 'modem_id'))->andWhere(['type'=>'in'])->andWhere(['>','created_at',date('Y-m-1 00:00:00')])->andWhere(['<','created_at',date('Y-m-t 00:00:00')])->sum('byte_count');
        if(empty($count)){
            return 0;
        }else{
            return $count;
        }
    }

    public function getMonthTrafficOutSum()
    {
          $count=$this->hasMany(Traffic::className(), array('modem_id' => 'modem_id'))->andWhere(['type'=>'out'])->andWhere(['>','created_at',date('Y-m-1 00:00:00')])->andWhere(['<','created_at',date('Y-m-t 00:00:00')])->sum('byte_count');

        if(empty($count)){
            return 0;
        }else{
            return $count;
        }
    }

    public function getDayTrafficInSum()
    {
        $count=$this->hasMany(Traffic::className(), array('modem_id' => 'modem_id'))->andWhere(['type'=>'in'])->andWhere(['>','created_at',date('Y-m-d 00:00:00')])->andWhere(['<','created_at',date('Y-m-d 23:59:59')])->sum('byte_count');
        if(empty($count)){
            return 0;
        }else{
            return $count;
        }
    }

    public function getDayTrafficOutSum()
    {
        $count=$this->hasMany(Traffic::className(), array('modem_id' => 'modem_id'))->andWhere(['type'=>'out'])->andWhere(['>','created_at',date('Y-m-d 00:00:00')])->andWhere(['<','created_at',date('Y-m-d 23:59:59')])->sum('byte_count');

        if(empty($count)){
            return 0;
        }else{
            return $count;
        }
    }

    public function getLastTrafficInSum()
    {
        $count=$this->hasMany(Traffic::className(), array('modem_id' => 'modem_id'))->andWhere(['type'=>'in'])->orderBy(['created_at'=>SORT_DESC])->one();
        if(empty($count)){
            return 0;
        }else{
            return $count->byte_count;
        }
    }

    public function getLastTrafficOutSum()
    {
        $count=$this->hasMany(Traffic::className(), array('modem_id' => 'modem_id'))->andWhere(['type'=>'out'])->orderBy(['created_at'=>SORT_DESC])->one();

        if(empty($count)){
            return 0;
        }else{
            return $count->byte_count;
        }
    }


}
