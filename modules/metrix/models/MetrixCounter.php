<?php

namespace app\modules\metrix\models;

use app\models\Address;
use app\models\CorrectorToCounter;
use app\models\ModemStatus;
use app\models\SimCard;
use app\models\Traffic;
use app\modules\metrix\components\MetrixCommandGenerator;
use Yii;

/**
 * This is the model class for table "metrix_counters".
 *
 * @property integer $id
 * @property integer $modem_id
 * @property integer $serial_number
 * @property string $valve_status
 * @property integer $geo_location_id
 * @property string $updated_at
 * @property string $created_at
 */
class MetrixCounter extends \yii\db\ActiveRecord
{
    const ChangeTimeIntervalType=3;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'metrix_counters';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['modem_id', 'serial_number', 'geo_location_id'], 'integer'],

            [['valve_status','updated_at', 'created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'modem_id' => Yii::t('metrix','modem_number'),
            'serial_number' => Yii::t('metrix','counter_number'),
            'valve_status' => Yii::t('metrix','valve_status'),
            'geo_location_id' => Yii::t('metrix','geo_location_id'),
            'updated_at' => Yii::t('metrix','updated_at'),'Updated At',
            'created_at' => Yii::t('metrix','created_at'),'Created At',
            'fulladdress'=>Yii::t('metrix','fulladdress'),
            'currentIndications'=>Yii::t('metrix','currentIndications'),
            'account'=>Yii::t('metrix','account'),
            'currentDate'=>Yii::t('metrix','currentDate'),

        ];
    }

    public function getAccount() {
        return "111111111111";
    }

    public function getCurrentDate() {
        return date("Y-m-d");
    }


    public function getAddress() {
        return $this->hasOne(Address::className(), array('id' => 'geo_location_id'));
    }

    public function getAlerts() {
        return $this->hasMany(MetrixAlert::className(), array('serial_number' => 'rmodule_id'));
    }


    public function getFlatindications() {

        return $this->getLastFlatIndications2($this->id) - $this->getFirstFlatIndications2($this->id);
    }



    public function getFirstFlatIndications($id, $limit = 1) {

        if (Yii::$app->request->isPost) {
            $beginDate = Yii::$app->request->post('beginDate', Yii::$app->params['beginDate']);
            $endDate = Yii::$app->request->post('endDate', 0);
        } else {
            $beginDate = Yii::$app->request->get('beginDate', Yii::$app->params['beginDate']);
            $endDate = Yii::$app->request->get('endDate', '0');
        }



        $query = MetrixIndication::find()->where('counter_id = :counter_id', [':counter_id' => $id]);

        // $query = $this->hasMany('app\models\UserIndications', array('user_counter_id' => 'id'))->orderBy('user_indications.created_at')->limit($limit);

        if ($beginDate) {
            $query->andWhere('metrix_indications.created_at >= :beginDate', [':beginDate' => $beginDate . ' 00:00:00']);
        }

        if ($endDate) {
            $query->andWhere('metrix_indications.created_at <= :endDate', [':endDate' => $endDate . ' 23:59:59']);
        }

        $query = $query->orderBy('metrix_indications.created_at')->limit($limit)->all();


        return $this->sum2($query);
    }

    public function getFirstFlatIndications2($id, $limit = 1) {

        if (Yii::$app->request->isPost) {
            $beginDate = Yii::$app->request->post('beginDate', Yii::$app->params['beginDate']);
            $endDate = Yii::$app->request->post('endDate', 0);
        } else {
            $beginDate = Yii::$app->request->get('beginDate', Yii::$app->params['beginDate']);
            $endDate = Yii::$app->request->get('endDate', '0');
        }



        $query = MetrixIndication::find()->where('counter_id = :counter_id', [':counter_id' => $id]);

        // $query = $this->hasMany('app\models\UserIndications', array('user_counter_id' => 'id'))->orderBy('user_indications.created_at')->limit($limit);

        if ($beginDate) {
            $query->andWhere('metrix_indications.created_at <= :beginDate', [':beginDate' => $beginDate . ' 00:00:00']);
        }

        /* if ($endDate) {
             $query->andWhere('indications.created_at <= :endDate', [':endDate' => $endDate . ' 23:59:59']);
         }*/

        $query = $query->orderBy(['metrix_indications.created_at'=>SORT_DESC])->limit($limit)->all();


        return $this->sum2($query);
    }


    public function getLastFlatIndications($id, $limit = 1) {

        if (Yii::$app->request->isPost) {
            $beginDate = Yii::$app->request->post('beginDate', Yii::$app->params['beginDate']);
            $endDate = Yii::$app->request->post('endDate', 0);
        } else {
            $beginDate = Yii::$app->request->get('beginDate', Yii::$app->params['beginDate']);
            $endDate = Yii::$app->request->get('endDate', 0);
        }

        $query = MetrixIndication::find()->where('counter_id = :counter_id', [':counter_id' => $id]);


        if ($beginDate) {
            $query->andWhere('metrix_indications.created_at >= :beginDate', [':beginDate' => $beginDate . ' 00:00:00']);
        }

        if ($endDate) {
            $query->andWhere('metrix_indications.created_at <= :endDate', [':endDate' => $endDate . ' 23:59:59']);
        }

        $query = $query->orderBy(['metrix_indications.created_at' => SORT_DESC])->limit($limit)->all();

        return $this->sum2($query);
    }

    public function getLastFlatIndications2($id, $limit = 1) {

        if (Yii::$app->request->isPost) {
            $beginDate = Yii::$app->request->post('beginDate', Yii::$app->params['beginDate']);
            $endDate = Yii::$app->request->post('endDate', 0);
        } else {
            $beginDate = Yii::$app->request->get('beginDate', Yii::$app->params['beginDate']);
            $endDate = Yii::$app->request->get('endDate', 0);
        }

        $query = MetrixIndication::find()->where('counter_id = :counter_id', [':counter_id' => $id]);


        // if ($beginDate) {
        //   $query->andWhere('indications.created_at >= :beginDate', [':beginDate' => $beginDate . ' 00:00:00']);
        //}

        if ($endDate) {
            $query->andWhere('metrix_indications.created_at <= :endDate', [':endDate' => $endDate . ' 23:59:59']);
        }

        $query = $query->orderBy(['metrix_indications.created_at' => SORT_DESC])->limit($limit)->all();

        return $this->sum2($query);
    }


    public function sum2($query) {
        $sum = 0;
        foreach ($query as $qv) {

            if (isset($qv->indications)) {
                $sum+=$qv->indications;
            }
        }

        return $sum;
    }

    public function getCurrentIndications() {

        $indications = MetrixIndication::find()->where('counter_id=:counter_id', [':counter_id' => $this->id]);

        $indications = $indications->orderBy(['created_at' => SORT_DESC])->one();

        if ($indications) {
            $result = $indications->indications;
        } else {
            $result = '0';
        }

        return $result;
    }

    public function getFlatIndicationsForPeriod($id) {

        return $this->getLastFlatIndications($id) - $this->getFirstFlatIndications($id);
    }

    public function getFulladdress() {
        $model=$this->hasOne(Address::className(), array('id' => 'geo_location_id'))->one();

        if(isset($model->street)){
            return $model->fulladdress;}
    }

    public function getFulladdresswithcity() {
        $model=$this->hasOne(Address::className(), array('id' => 'geo_location_id'))->one();

        if(isset($model->street)){
            return 'г. '.$model->region->name.' '.$model->fulladdress;

        }
    }

    public function getModemStatus()
    {
        return $this->hasOne(ModemStatus::className(), array('modem_id' => 'modem_id'));
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


    public function getPerformedOperation(){

       $commands=$this->hasMany(MetrixCommandConveyor::className(), ['modem_id' => 'modem_id'])->andWhere(['status'=>'ACTIVE'])->all();



        if(!empty($commands)){
            foreach($commands as $command){
                switch($command->command){
                    case MetrixCommandGenerator::GetOpenValveWithLeakCheckCommand():
                        return 'OpenValveWithLeakCheck';break;
                    case MetrixCommandGenerator::GetOpenValveWithLeakCheckAndKeyConfirmCommand():
                        return 'OpenValveWithLeakCheckAndKeyConfirm';break;
                    case MetrixCommandGenerator::GetForceOpenValveCommand():
                        return 'ForceOpenValve';break;
                    case MetrixCommandGenerator::GetCloseValveCommand():
                        return 'CloseValve';break;
                }
                return false;
            }

        }else{
            return false;
        }

    }

    public function getLastCommand(){

        $command=$this->hasMany(MetrixCommandConveyor::className(), ['modem_id' => 'modem_id'])
            ->andWhere(['status'=>'ACTIVE'])
            ->orderBy(['created_at'=>SORT_DESC])
            ->one();

        if(!empty($command)){
          return $command;

        }else{
            return false;
        }

    }

      public function getChangeTimeIntervalCommand(){

        $command=$this->hasMany(MetrixCommandConveyor::className(), ['modem_id' => 'modem_id'])
            ->andWhere(['status'=>'ACTIVE'])
            ->andWhere(['command_type'=>self::ChangeTimeIntervalType])
            ->orderBy(['created_at'=>SORT_DESC])
            ->one();

        if(!empty($command)){
          return $command;

        }else{
            return false;
        }

    }

    public static function getFirstFlatIndicationsStatic($id, $beginDate, $endDate, $limit = 1) {

        $query = MetrixIndication::find()->where('counter_id = :counter_id', [':counter_id' => $id]);

        // $query = $this->hasMany('app\models\UserIndications', array('user_counter_id' => 'id'))->orderBy('user_indications.created_at')->limit($limit);

        if ($beginDate) {
            $query->andWhere('metrix_indications.created_at >= :beginDate', [':beginDate' => $beginDate . ' 23:59:59']);
        }

        if ($endDate) {
            $query->andWhere('metrix_indications.created_at <= :endDate', [':endDate' => $endDate . ' 23:59:59']);
        }

        $query = $query->orderBy('metrix_indications.created_at')->limit($limit)->all();


        return self::sum2($query);
    }

    public static function getLastFlatIndicationsStatic($id, $beginDate, $endDate, $limit = 1) {


        $query = MetrixIndication::find()->where('counter_id = :counter_id', [':counter_id' => $id]);


        if ($beginDate) {
            $query->andWhere('metrix_indications.created_at >= :beginDate', [':beginDate' => $beginDate . ' 23:59:59']);
        }

        if ($endDate) {
            $query->andWhere('metrix_indications.created_at <= :endDate', [':endDate' => $endDate . ' 23:59:59']);
        }

        $query = $query->orderBy(['metrix_indications.created_at' => SORT_DESC])->limit($limit)->all();

        return self::sum2($query);
    }

    public function getCorrector()
    {
       return $this->hasOne(CorrectorToCounter::className(),['modem_id'=>'modem_id'])->one();
    }

    public function getTraffic()
    {
        return $this->hasOne(Traffic::className(),['modem_id'=>'modem_id'])->one();
    }


}
