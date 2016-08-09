<?php

namespace app\models;

use Yii;
use app\components\CounterQuery;

/**
 * This is the model class for table "user_counters".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $user_modem_id
 * @property integer $serial_number
 * @property integer $initial_indications
 * @property integer $last_indications
 * @property integer $last_impuls
 * @property integer $battery_level
 * @property string $tamper_detect
 * @property integer $tamper_detect_key
 * @property string $updated_at
 * @property string $created_at
 */
class UserCounters extends \yii\db\ActiveRecord {

    public $resizedImage;
    public $image;
  

  
   
    /**
     * @inheritdoc
     */
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

    public static function tableName() {
        return 'user_counters';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            /* [['user_id', 'initial_indications', 'user_modem_id', 'serial_number', 'last_indications', 'battery_level', 'tamper_detect_key'], 'integer'],
              [['initial_indications','last_indications'], 'double'],
              [['flat', 'geo_location_id'], 'integer'],
              [['account'], 'string', 'max' => 255],
              [['tamper_detect, updated_at', 'created_at', 'where_installed', 'type'], 'safe'], */

            //editCounter();
            //[['user_id'],'exist','targetAttribute'=>'id','targetClass'=>'\app\models\User','message'=>'User not found','on'=>'editCounter'],
            // ['resizedImage','safe', 'when' => function ($model) {return !empty($model->real_serial_number);},'on'=>'editCounter'],
            // ['real_serial_number','required', 'when' => function ($model) {return $model->resizedImage->name=='';},
            //'message'=>'Необходимо заполнить «№ Счетчика»  или «Фотография счетчика» ','on'=>'editCounter'],
            ['image', 'required', 'when' => function ($model, $attribute) {
                    return $model->real_serial_number == '';
                },
                'message' => 'Необходимо заполнить «№ Счетчика»  или «Фотография счетчика» ', 'on' => 'editCounter'],
            [['initial_indications', 'last_indications'], 'double', 'on' => 'editCounter'],
            ['resizedImage', 'image', 'extensions' => 'png,jpg', 'skipOnEmpty' => true, 'on' => 'editCounter'],
            [['flat', 'geo_location_id', 'update_interval', 'battery_level'], 'integer', 'on' => 'editCounter'],
            [['serial_number'], 'number', 'integerOnly' => true, 'on' => 'editCounter'],
            [['account', 'counter_model', 'user_modem_id', 'serial_number', 'real_serial_number'], 'string', 'max' => 255, 'on' => 'editCounter'],
            ['user_modem_id', 'exist', 'targetClass' => UserModems::className(), 'targetAttribute' => 'serial_number'],
            [['tamper_detect', 'month_update', 'month_update_type', 'inn', 'phone', 'user_id', 'fullname', 'user_type', 'updated_at', 'created_at', 'where_installed', 'type', 'tamper_detect_key'], 'safe', 'on' => 'editCounter'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'type'=>'Тип',
            'user_id' => 'Пользователь',
            'user_type' => 'Тип пользователя',
            'user_modem_id' => '№ Модема',
            'real_serial_number' => '№ Счетчика',
            'serial_number' => '№ Радиомодуля',
            'account' => 'Лицевой счет',
            'identifier' => 'Идентификатор',
            'initial_indications' => 'Начальные Показания счетчика',
            'last_indications' => 'Последние Показания счетчика',
            'battery_level' => 'Battery Level',
            'tamper_detect' => 'Tamper Detect',
            'tamper_detect_key' => 'Tamper Detect Key',
            'updated_at' => 'Дата и время',
            'created_at' => 'Created At',
            'flat' => 'Квартира',
            'geo_location_id' => 'Адрес',
            'fulladdress' => 'Адрес',
            'fulladdresswithcity' => 'Адрес',
            'counter_model' => 'Модель счетчика',
            'update_interval' => 'Частота обновления( в часах)',
            'fullname' => 'Ф.И.О.',
            'resizedImage' => 'Фотография счетчика',
            'month_update' => 'Месячное обновление',
            'is_ignore_alert' => 'Отключить предупреждения',
        ];
    }

    public function getUser() {
        return $this->hasOne('app\models\User', array('id' => 'user_id'));
    }

    public function getIndication() {

        return $this->hasMany('app\models\UserIndications', array('user_counter_id' => 'serial_number'))->all();
    }

    public function getIndications() {

        $beginDate = Yii::$app->request->get('beginDate', 0);
        $endDate = Yii::$app->request->get('endDate', 0);

        $query = $this->hasMany('app\models\UserIndications', array('user_counter_id' => 'id'));

        if ($beginDate)
            $query->andWhere('created_at > :beginDate', [':beginDate' => $beginDate]);

        if ($endDate)
            $query->andWhere('created_at < :endDate', [':endDate' => $endDate]);

        return $query;
    }

    public function getSumIndications() {

        $beginDate = Yii::$app->request->get('beginDate', 0);
        $endDate = Yii::$app->request->get('endDate', 0);

        $query = $this->hasMany('app\models\UserIndications', array('user_counter_id' => 'id'));

        if ($beginDate)
            $query->andWhere('created_at >= :beginDate', [':beginDate' => $beginDate]);
        if ($endDate)
            $query->andWhere('created_at =< :endDate', [':endDate' => $endDate]);

        return $query->sum('indications');
    }

    //30.707309
    //46.399297

    public function getModem() {
        return $this->hasOne('app\models\UserModems', array('serial_number' => 'user_modem_id'));
    }

    public function getModel() {
        return $this->hasOne('app\models\CounterModels', array('id' => 'counter_model'));
    }

    public function getDayConsumption($beginDate, $endDate, $previousDayConsumption) {
        $indications = UserIndications::find()
                ->where('created_at > :beginDate AND created_at < :endDate', [':beginDate' => $beginDate, ':endDate' => $endDate])
                ->andWhere(['user_counter_id' => $this->serial_number])
                ->orderBy(['created_at' => SORT_DESC])
                ->limit(1);


        return $indications->indications - $previousDayConsumption;
    }

    public function getAddress() {
        return $this->hasOne('app\models\CounterAddress', array('id' => 'geo_location_id'));
    }
    
     public function getFulladdress() {
       $model=$this->hasOne('app\models\CounterAddress', array('id' => 'geo_location_id'))->one();
       
       if(isset($model->street)){
       return $model->fulladdress;}
    }



    public function getAlerts() {
        return $this->hasMany('app\models\AlertsList', array('serial_number' => 'serial_number'));
    }

    public function getIndicationsForPeriod() {
        return $this->last_indications - $this->initial_indications;
    }

    public function getHouseIndicationsForPeriod() {

        $flatCounters = UserCounters::find()->where('geo_location_id =:geo_location_id AND flat!=0', [':geo_location_id' => $this->geo_location_id]);


        if (array_key_exists('user', Yii::$app->authManager->getRolesByUser(Yii::$app->user->id))) {
            //добавляем условие   для  role
            $flatCounters->andWhere('user_id = :userId', [':userId' => Yii::$app->user->id]);
        }

        $flatCounters = $flatCounters->count();

        $sum = $this->getLastIndications($flatCounters) - $this->getFirstIndications($flatCounters);

        return $sum;
    }

    public function getFlatIndicationsForPeriod($id) {

        return $this->getLastFlatIndications($id) - $this->getFirstFlatIndications($id);
    }

    public function getFlatindications() {

        return $this->getLastFlatIndications($this->serial_number) - $this->getFirstFlatIndications($this->serial_number);
    }

    public function getFirstFlatIndications($id, $limit = 1) {

        if (Yii::$app->request->isPost) {
            $beginDate = Yii::$app->request->post('beginDate', Yii::$app->params['beginDate']);
            $endDate = Yii::$app->request->post('endDate', 0);
        } else {
            $beginDate = Yii::$app->request->get('beginDate', Yii::$app->params['beginDate']);
            $endDate = Yii::$app->request->get('endDate', '0');
        }



        $query = UserIndications::find()->where('user_counter_id = :user_counter_id', [':user_counter_id' => $id]);

        // $query = $this->hasMany('app\models\UserIndications', array('user_counter_id' => 'id'))->orderBy('user_indications.created_at')->limit($limit);

        if ($beginDate) {
            $query->andWhere('user_indications.created_at >= :beginDate', [':beginDate' => $beginDate . ' 00:00:00']);
        }

        if ($endDate) {
            $query->andWhere('user_indications.created_at <= :endDate', [':endDate' => $endDate . ' 23:59:59']);
        }

        $query = $query->orderBy('user_indications.created_at')->limit($limit)->all();


        return $this->sum2($query);
    }

    public static function getFirstFlatIndicationsStatic($id, $beginDate, $endDate, $limit = 1) {

        $query = UserIndications::find()->where('user_counter_id = :user_counter_id', [':user_counter_id' => $id]);

        // $query = $this->hasMany('app\models\UserIndications', array('user_counter_id' => 'id'))->orderBy('user_indications.created_at')->limit($limit);

        if ($beginDate) {
            $query->andWhere('user_indications.created_at >= :beginDate', [':beginDate' => $beginDate . ' 23:59:59']);
        }

        if ($endDate) {
            $query->andWhere('user_indications.created_at <= :endDate', [':endDate' => $endDate . ' 23:59:59']);
        }

        $query = $query->orderBy('user_indications.created_at')->limit($limit)->all();


        return UserCounters::sum2($query);
    }

    public static function getLastFlatIndicationsStatic($id, $beginDate, $endDate, $limit = 1) {


        $query = UserIndications::find()->where('user_counter_id = :user_counter_id', [':user_counter_id' => $id]);


        if ($beginDate) {
            $query->andWhere('user_indications.created_at >= :beginDate', [':beginDate' => $beginDate . ' 23:59:59']);
        }

        if ($endDate) {
            $query->andWhere('user_indications.created_at <= :endDate', [':endDate' => $endDate . ' 23:59:59']);
        }

        $query = $query->orderBy(['user_indications.created_at' => SORT_DESC])->limit($limit)->all();

        return UserCounters::sum2($query);
    }

    public function getLastFlatIndications($id, $limit = 1) {

        if (Yii::$app->request->isPost) {
            $beginDate = Yii::$app->request->post('beginDate', Yii::$app->params['beginDate']);
            $endDate = Yii::$app->request->post('endDate', 0);
        } else {
            $beginDate = Yii::$app->request->get('beginDate', Yii::$app->params['beginDate']);
            $endDate = Yii::$app->request->get('endDate', 0);
        }

        $query = UserIndications::find()->where('user_counter_id = :user_counter_id', [':user_counter_id' => $id]);


        if ($beginDate) {
            $query->andWhere('user_indications.created_at >= :beginDate', [':beginDate' => $beginDate . ' 00:00:00']);
        }

        if ($endDate) {
            $query->andWhere('user_indications.created_at <= :endDate', [':endDate' => $endDate . ' 23:59:59']);
        }

        $query = $query->orderBy(['user_indications.created_at' => SORT_DESC])->limit($limit)->all();

        return $this->sum2($query);
    }

    public function getFirstIndications($limit = 1) {

        $beginDate = Yii::$app->request->get('beginDate', 0);
        $endDate = Yii::$app->request->get('endDate', 0);


        $counterList = CounterQuery::counterIdQueryByRole($this->geo_location_id);
        $query = UserIndications::find()->where('user_counter_id IN(' . $counterList->createCommand()->getRawSql() . ')');


        // $query = $this->hasMany('app\models\UserIndications', array('user_counter_id' => 'id'))->orderBy('user_indications.created_at')->limit($limit);

        if ($beginDate) {
            $query->andWhere('user_indications.created_at >= :beginDate', [':beginDate' => $beginDate]);
        }

        if ($endDate) {
            $query->andWhere('user_indications.created_at <= :endDate', [':endDate' => $endDate]);
        }

        $result = $query->orderBy('user_indications.created_at')->limit($limit)->all();


        return $this->sum2($result);
    }

    public function getLastIndications($limit = 1) {

        $beginDate = Yii::$app->request->get('beginDate', 0);
        $endDate = Yii::$app->request->get('endDate', 0);
        $geoId = Yii::$app->request->get('city', 0);
        //$query = UserIndications::find()->where('user_counter_id IN(:user_counter_id)', [':user_counter_id'=>$userCounterIdArray]);
        //$query = UserIndications::find()->select('*')->where('user_counter_id IN(SELECT id FROM user_counters WHERE geo_location_id= :geo_location_id)', [':geo_location_id' => $this->geo_location_id]);
        //$query = $this->hasMany('app\models\UserIndications', array('user_counter_id' => 'id'))->orderBy(['user_indications.created_at' => SORT_DESC])->limit(201);
        /* $counterList = UserCounters::find();


          if (array_key_exists('admin', Yii::$app->authManager->getRolesByUser(Yii::$app->user->id))) {
          //добавляем условие   для  role
          $counterList->select('id');
          if ($geoId) {
          $counterList->andWhere('counter_address.region_id = :geo_location_id', [':geo_location_id' => $geoId]);
          }
          }

          if (array_key_exists('admin_region', Yii::$app->authManager->getRolesByUser(Yii::$app->user->id))) {
          //добавляем условие   для  role
          $counterList->select('id')->where('user_counters.flat = :flat', [':flat' => '0'])->joinWith('user')->joinWith('address');
          $user = User::find()->where(['id' => Yii::$app->user->id])->one();
          $counterList->andWhere('counter_address.region_id = :geo_location_id', [':geo_location_id' => $user->geo_location_id]);
          }

          if (array_key_exists('user', Yii::$app->authManager->getRolesByUser(Yii::$app->user->id))) {
          //добавляем условие   для  role
          $counterList->select('id')->where('user_id = :user_id', [':user_id' => Yii::$app->user->id])->joinWith('user')->joinWith('address');
          }

          echo $counterList->sql; */
        $counterList = CounterQuery::counterIdQueryByRole($this->geo_location_id);
        $query = UserIndications::find()->where('user_counter_id IN(' . $counterList->createCommand()->getRawSql() . ')');
        if ($beginDate) {
            $query->andWhere('user_indications.created_at > :beginDate', [':beginDate' => $beginDate . ' 00:00:00']);
        }

        if ($endDate) {
            $query->andWhere('user_indications.created_at < DATE_ADD(DATE(:endDate),INTERVAL 1 DAY)', [':endDate' => $endDate . ' 23:59:59']);
        }

        $query = $query->orderBy(['user_indications.created_at' => SORT_DESC])->limit($limit)->all();

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

        $indications = UserIndications::find()->where('user_counter_id=:user_counter_id', [':user_counter_id' => $this->serial_number]);

        $indications = $indications->orderBy(['created_at' => SORT_DESC])->one();

        if ($indications) {
            $result = $indications->indications;
        } else {
            $result = '0';
        }

        return $result;
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
    
     public function getFulladdresswithcity() {
       $model=$this->hasOne('app\models\CounterAddress', array('id' => 'geo_location_id'))->one();
       
       if(isset($model->street)){
       return 'город '.$model->region->name.' '.$model->fulladdress;
       
       }
    }

}
