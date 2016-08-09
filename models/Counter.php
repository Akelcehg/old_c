<?php

namespace app\models;

use app\components\Search;
use Yii;
use yii\db\ActiveRecord;
use yii\validators\ExistValidator;

/**
 * This is the model class for table "counters".
 *
 * @property string $user_id
 * @property string $modem_id
 * @property string $serial_number
 * @property string $type
 * @property integer $counter_model
 * @property double $initial_indications
 * @property double $last_indications
 * @property integer $geo_location_id
 * @property string $user_type
 * @property string $fullname
 * @property string $inn
 * @property string $phone
 * @property string $account
 * @property integer $flat
 * @property string $updated_at
 * @property string $created_at
 * @property integer $rmodule_id
 * @property integer $id
 */
class Counter extends ActiveRecord
{
    public $resizedImage;
    public $image;

    const COUNTER_TYPE_GAS = 'gas';
    const COUNTER_TYPE_WATER = 'water';

    public static function getCounterTypeList() {
        return [
            self::COUNTER_TYPE_GAS => 'газ',
            self::COUNTER_TYPE_WATER => 'вода',
        ];
    }

    public function getCounterTypeText() {
        $counterTypeList = self::getCounterTypeList();
        if (isset($counterTypeList[$this->type])) {
            return $counterTypeList[$this->type];
        } else {
            return $this->type;
        }
    }

    const USER_TYPE_INDIVIDUAL = 'individual';
    const USER_TYPE_LEGAL_ENTITY = 'legal_entity';
    const USER_TYPE_HOUSE_METERING = 'house_metering';
    const USER_TYPE_NULL = NULL;

    public static function getUserTypeList() {
        return [
            self::USER_TYPE_INDIVIDUAL => 'Физ.',
            self::USER_TYPE_LEGAL_ENTITY => 'Юр.',
            self::USER_TYPE_HOUSE_METERING => 'Общедомовой узел учета',
            self::USER_TYPE_NULL => 'Не задано',
        ];
    }

    public function getUserTypeText() {
        $userTypeList = self::getUserTypeList();
        if (isset($userTypeList[$this->user_type])) {
            return $userTypeList[$this->user_type];
        } else {
            return $this->user_type;
        }
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'counters';
    }



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['image', 'required', 'when' => function ($model, $attribute) {
                return $model->serial_number == '';
            },
                'message' => 'Необходимо заполнить «№ Счетчика»  или «Фотография счетчика» ', 'on' => 'editCounter'],
            [['initial_indications', 'last_indications'], 'double', 'on' => 'editCounter'],
            ['resizedImage', 'image', 'extensions' => 'png,jpg', 'skipOnEmpty' => true, 'on' => 'editCounter'],
            [['geo_location_id', ], 'integer', 'on' => 'editCounter'],
            [['serial_number'], 'number', 'integerOnly' => true, 'on' => 'editCounter'],
            [['account', 'modem_id'], 'string', 'max' => 255, 'on' => 'editCounter'],
            [['flat'], 'string', 'max' => 25, 'on' => 'editCounter'],
            ['counter_model', 'exist', 'targetClass' => CounterModel::className(), 'targetAttribute' => 'id'],
            ['modem_id', 'exist', 'targetClass' => Modem::className(), 'targetAttribute' => 'serial_number'],
            ['rmodule_id','isUnique'],
            ['rmodule_id', 'exist', 'targetClass' => Rmodule::className(), 'targetAttribute' => 'serial_number','message'=>'Такого радиомодуля\входа не существует'],
            [['serial_number'], 'number', 'integerOnly' => true],

            [['inn', 'phone', 'user_id', 'fullname', 'user_type', 'updated_at', 'created_at','type'], 'safe', 'on' => 'editCounter'],

        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        Search::Indexing($this);
    }




    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('counter','ID'),// 'ID',
            'type'=> Yii::t('counter','Type'),//'Тип',
            'user_id' => Yii::t('counter','User id'),//'Пользователь',
            'user_type' => Yii::t('counter','User type'),//'Тип абонента',
            'modem_id' => Yii::t('counter','Modem id'),//'№ Модема',
            'real_serial_number' => Yii::t('counter','real_serial_number'),//'№ Счетчика',
            'serial_number' => Yii::t('counter','serial_number'),// '№ Счетчика',
            'rmodule_id' => Yii::t('counter','rmodule_id'),//'№ Радиомодуля',
            'account' => Yii::t('counter','account'),//'Лицевой счет',
            'identifier' =>Yii::t('counter','identifier'),// 'Идентификатор',
            'initial_indications' => Yii::t('counter','initial_indications'),// 'Начальные Показания счетчика',
            'last_indications' => Yii::t('counter','last_indications'),// 'Последние Показания счетчика',
            'battery_level' => Yii::t('counter','battery_level'),// 'Battery Level',
            'tamper_detect' => Yii::t('counter','tamper_detect'),// 'Tamper Detect',
            'tamper_detect_key' => Yii::t('counter','tamper_detect_key'),// Tamper Detect Key',
            'updated_at' => Yii::t('counter','updated_at'),// 'Дата и время',
            'created_at' => Yii::t('counter','created_at'),// 'Created At',
            'flat' => Yii::t('counter','flat'),// 'Квартира',
            'geo_location_id' => Yii::t('counter','geo_location_id'),// 'Адрес',
            'flatindications' => Yii::t('counter','consump_period'),// 'Потребление за период',
            'fulladdress' => Yii::t('counter','fulladdress'),// 'Адрес',
            'fulladdresswithcity' => Yii::t('counter','fulladdresswithcity'),// 'Адрес',
            'counter_model' => Yii::t('counter','counter_model'),// 'Модель счетчика',
            'update_interval' => Yii::t('counter','update_interval'),// 'Частота обновления( в часах)',
            'fullname' => Yii::t('counter','fullname'),// 'Ф.И.О.',
            'resizedImage' => Yii::t('counter','resizedImage'),// 'Фотография счетчика',
            'month_update' => Yii::t('counter','month_update'),// 'Месячное обновление',
            'is_ignore_alert' => Yii::t('counter','is_ignore_alert'),// 'Отключить предупреждения',
            'period_begin'=>Yii::t('counter','period_begin'),
            'period_end'=>Yii::t('counter','period_end'),
            'current_indication'=>Yii::t('counter','current_indication'),
            'exploitation'=>Yii::t('counter','exploitation'),
            'city' => Yii::t('counter','City'),

        ];
    }

    private function getRmodMess()
    {



        if($c=Rmodule::findOne(['serial_number'=>$this->rmodule_id])){


            $b='Радиомодуль № '.$c->serial_number.' привязан к счетчику  №'.$c->counter->serial_number;

        }
        return $b;

    }

    public function isRmoduleNotExist($rmodule_id)
    {
        if (Counter::find()->where(['rmodule_id' => $this->rmodule_id])->count() > 0) {

            $this->addError('rmodule_id', $this->getRmodMess());
            return false;

        }else{
            return true;
        }

    }

    public function isUnique($attribute,$params)
    {



         if ($this->isAttributeChanged('rmodule_id',false)) {

             //echo $this->getOldAttribute('rmodule_id').' - '.$this->rmodule_id;
            if($this->isRmoduleNotExist($this->rmodule_id)){
                return true;
            }
             else
             {
                 return false;
             }

         }
         else
         {
             return true;
         }
    }

    public function getModem() {
        return $this->hasOne(Modem::className(), array('serial_number' => 'modem_id'));
    }

    public function getUpdate_interval() {

        return $this->rmodule->update_interval;
    }

    public function getMonth_update() {


            return $this->rmodule->month_update;

    }

    public function getMonth_update_type() {

        return $this->rmodule->month_update_type;
    }


    public function getRmodule() {

        if(!$rmodule=$this->hasOne(Rmodule::className(), array('serial_number' => 'rmodule_id'))){
            $rmodule = false;
        }
        return $rmodule;
    }

    public function getUser() {
        return $this->hasOne(User::className(), array('id' => 'user_id'));
    }

    public function getAddress() {
        return $this->hasOne(Address::className(), array('id' => 'geo_location_id'));
    }

    public function getAlerts() {
        return $this->hasMany(AlertsList::className(), array('serial_number' => 'rmodule_id'))->where(['device_type'=>'counter']);
    }


    public function getFlatindications() {

        return $this->getLastFlatIndications2($this->id) - $this->getFirstFlatIndications2($this->id);
    }



    public function getMonthIndications() {

        return round($this->flatindications, 3);
    }

    public function getDayAverageIndications() {

        return round($this->flatindications / date("d"), 3);
    }



    public function getFirstFlatIndications($id, $limit = 1) {

        if (Yii::$app->request->isPost) {
            $beginDate = Yii::$app->request->post('beginDate', Yii::$app->params['beginDate']);
            $endDate = Yii::$app->request->post('endDate', 0);
        } else {
            $beginDate = Yii::$app->request->get('beginDate', Yii::$app->params['beginDate']);
            $endDate = Yii::$app->request->get('endDate', '0');
        }



        $query = Indication::find()->where('counter_id = :counter_id', [':counter_id' => $id]);

        // $query = $this->hasMany('app\models\UserIndications', array('user_counter_id' => 'id'))->orderBy('user_indications.created_at')->limit($limit);

        if ($beginDate) {
            $query->andWhere('indications.created_at >= :beginDate', [':beginDate' => $beginDate . ' 00:00:00']);
        }

        if ($endDate) {
            $query->andWhere('indications.created_at <= :endDate', [':endDate' => $endDate . ' 23:59:59']);
        }

        $query = $query->orderBy('indications.created_at')->limit($limit)->all();


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



        $query = Indication::find()->where('counter_id = :counter_id', [':counter_id' => $id]);

        // $query = $this->hasMany('app\models\UserIndications', array('user_counter_id' => 'id'))->orderBy('user_indications.created_at')->limit($limit);

        if ($beginDate) {
            $query->andWhere('indications.created_at <= :beginDate', [':beginDate' => $beginDate . ' 00:00:00']);
        }

       /* if ($endDate) {
            $query->andWhere('indications.created_at <= :endDate', [':endDate' => $endDate . ' 23:59:59']);
        }*/

$query = $query->orderBy(['indications.created_at'=>SORT_DESC])->limit($limit)->all();


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

        $query = Indication::find()->where('counter_id = :counter_id', [':counter_id' => $id]);


        if ($beginDate) {
            $query->andWhere('indications.created_at >= :beginDate', [':beginDate' => $beginDate . ' 00:00:00']);
        }

        if ($endDate) {
            $query->andWhere('indications.created_at <= :endDate', [':endDate' => $endDate . ' 23:59:59']);
        }

        $query = $query->orderBy(['indications.created_at' => SORT_DESC])->limit($limit)->all();

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

        $query = Indication::find()->where('counter_id = :counter_id', [':counter_id' => $id]);


       // if ($beginDate) {
         //   $query->andWhere('indications.created_at >= :beginDate', [':beginDate' => $beginDate . ' 00:00:00']);
        //}

        if ($endDate) {
            $query->andWhere('indications.created_at <= :endDate', [':endDate' => $endDate . ' 23:59:59']);
        }

        $query = $query->orderBy(['indications.created_at' => SORT_DESC])->limit($limit)->all();

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

        $indications = Indication::find()->where('counter_id=:counter_id', [':counter_id' => $this->id]);

        $indications = $indications->orderBy(['created_at' => SORT_DESC])->one();

        if ($indications) {
            $result = $indications->indications;
        } else {
            $result = '0';
        }

        return $result;
    }


    public static function getFirstFlatIndicationsStatic($id, $beginDate, $endDate, $limit = 1) {

        $query = Indication::find()->where('counter_id = :counter_id', [':counter_id' => $id]);

        // $query = $this->hasMany('app\models\UserIndications', array('user_counter_id' => 'id'))->orderBy('user_indications.created_at')->limit($limit);

        if ($beginDate) {
            $query->andWhere('indications.created_at >= :beginDate', [':beginDate' => $beginDate . ' 23:59:59']);
        }

        if ($endDate) {
            $query->andWhere('indications.created_at <= :endDate', [':endDate' => $endDate . ' 23:59:59']);
        }

        $query = $query->orderBy('indications.created_at')->limit($limit)->all();


        return Counter::sum2($query);
    }

    public static function getLastFlatIndicationsStatic($id, $beginDate, $endDate, $limit = 1) {


        $query = Indication::find()->where('counter_id = :counter_id', [':counter_id' => $id]);


        if ($beginDate) {
            $query->andWhere('indications.created_at >= :beginDate', [':beginDate' => $beginDate . ' 23:59:59']);
        }

        if ($endDate) {
            $query->andWhere('indications.created_at <= :endDate', [':endDate' => $endDate . ' 23:59:59']);
        }

        $query = $query->orderBy(['indications.created_at' => SORT_DESC])->limit($limit)->all();

        return Counter::sum2($query);
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
            return 'город '.$model->region->name.' '.$model->fulladdress;

        }
    }



    public function getTitledArray($fields=[]) {

        $titleArray=[];

        foreach($fields as $field){

            switch ($field) {
                case 'user_type':
                    $value=$this->getUserTypeList()[$this->$field];
                    break;

                default:
                    $value=empty($this->$field)?'-':$this->$field;
                    break;
            }



            $titleArray[]=[$this->attributeLabels()[$field],$value];

        }

        return $titleArray;
    }




}
