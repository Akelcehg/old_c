<?php

namespace app\models;

use Yii;
use app\components\CounterQuery;

/**
 * This is the model class for table "counter_address".
 *
 * @property integer $id
 * @property integer $counter_id
 * @property integer $region_id
 * @property string $address
 * @property string $latitude
 * @property string $longitude
 * @property string $status
 */
class CounterAddress extends \yii\db\ActiveRecord {

    
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_DEACTIVATED = 'DEACTIVATED';
    const STATUS_DELETED = 'DELETED';
    
        
    /**
     * 
     * Get user rolres
     * @author Igor
     */
    public  function getAllStatuses() {
        return [
            self::STATUS_ACTIVE =>'Активно',
            self::STATUS_DEACTIVATED =>'Неактивно',
           
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
    
    public $count;
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['region_id', 'street','house','latitude','longitude'], 'required'],
            [['id', 'region_id'], 'integer'],
            [['street','house'], 'string'],
            [['exploitation'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'counter_id' => 'Counter ID',
            'region_id' => '№ Региона',
            'exploitation'=>'Сдан в эксплуатацию',
            'street' => 'Улица',
            'house' => 'Дом',
            'longitude'=>'Широта',
            'latitude'=>'Долгота',
            'status' => 'Статус',
        ];
    }
    

    public function getFulladdress() {
        
        return $this->street.' '.$this->house;
    }

    public function getFulladdresswithcity() {

        return  $this->getRegion()->one()->name .' '.$this->street.' '.$this->house;
    }

    public function getRegion() {
        return $this->hasOne('app\models\Regions', array('id' => 'region_id'));
    }

    public function getCounters() {
        return $this->hasMany('app\models\UserCounters', array('geo_location_id' => 'id'));
    } 
        
     public function getHouseIndicationsForPeriod() {


        $whereInstalled = Yii::$app->request->get('where_installed', 0);
         
        $flatCounters = UserCounters::find()->where('geo_location_id =:geo_location_id', [':geo_location_id' => $this->id])
                ->andWhere('user_counters.real_serial_number IS NOT NULL');
        
       
        if ($whereInstalled) {
            $flatCounters->andWhere('where_installed = :whereInstalled ', [':whereInstalled ' => $whereInstalled]);
        }
         
        
        
        if (array_key_exists('user', Yii::$app->authManager->getRolesByUser(Yii::$app->user->id))) {
            //добавляем условие   для  role
            $flatCounters->andWhere('user_id = :userId', [':userId' =>Yii::$app->user->id]);
        }

        $flatCountersAll=$flatCounters->all();
        
        $indications=0;
       
        
       
        foreach ($flatCountersAll as $counter)
            {
                $indications+=$counter->flatindications;
                
            }
        
       //$sum = $lastIndications- $firstIndications;
        //echo $this->getFirstIndications($beginDate,$endDate,$flatCountersCount).'|||';
        //$sum = $this->getLastIndications($beginDate,$endDate,$flatCountersCount) - $this->getFirstIndications($beginDate,$endDate,$flatCountersCount);

       // return $sum;
       return $indications;
    }
    
     public function getHouseCount() {


        $whereInstalled = Yii::$app->request->get('where_installed', 0);
         
        $flatCounters = UserCounters::find()->where('geo_location_id =:geo_location_id', [':geo_location_id' => $this->id])
                ->andWhere('user_counters.real_serial_number IS NOT NULL');
        
       
        if ($whereInstalled) {
            $flatCounters->andWhere('where_installed = :whereInstalled ', [':whereInstalled ' => $whereInstalled]);
        }
         
        
        
        if (array_key_exists('user', Yii::$app->authManager->getRolesByUser(Yii::$app->user->id))) {
            //добавляем условие   для  role
            $flatCounters->andWhere('user_id = :userId', [':userId' =>Yii::$app->user->id]);
        }

        
        
        $this->count=$flatCounters->count();
       
    }
    
     public function getFirstIndications($beginDate,$endDate,$limit = 1) {

        $counterList = CounterQuery::counterIdQueryByRole($this->id);
        
        $query = UserIndications::find()->where('user_counter_id IN('.$counterList->createCommand()->getRawSql().')');

        if ($beginDate) {
            $query->andWhere('user_indications.created_at >= DATE(:beginDate)', [':beginDate' => $beginDate ]);
        }

        if ($endDate) {
            $query->andWhere('user_indications.created_at <= DATE(:endDate)', [':endDate' => $endDate ]);
        }

        $result = $query->orderBy('user_indications.created_at')->limit($limit)->all();


        return $this->sum2($result);
    }

    public function getLastIndications($beginDate,$endDate,$limit = 1) {

        $counterList = CounterQuery::counterIdQueryByRole($this->id);
        
        $query = UserIndications::find()->where('user_counter_id IN('.$counterList->createCommand()->getRawSql().')');
        
        if ($beginDate) {
            $query->andWhere('user_indications.created_at >= DATE(:beginDate)', [':beginDate' => $beginDate ]);
        }

        if ($endDate) {
            $query->andWhere('user_indications.created_at <= DATE(:endDate)', [':endDate' => $endDate ]);
        }

        $result = $query->orderBy(['user_indications.created_at' => SORT_DESC])->limit($limit)->all();

        return $this->sum2($result);
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
}
