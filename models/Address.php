<?php

namespace app\models;

use Yii;



/**
 * This is the model class for table "Address".
 *
 * @property integer $id
 * @property integer $region_id
 * @property string $street
 * @property string $house
 * @property string $longitude
 * @property string $latitude
 * @property string $status
 * @property integer $exploitation
 */
class Address extends \yii\db\ActiveRecord
{
    public $count;

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

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['region_id', 'exist', 'targetClass' => Region::className(), 'targetAttribute' => 'id'],
            [['region_id', 'exploitation'], 'integer'],
            [['street', 'status'], 'string'],
            [['house'], 'string', 'max' => 8],
            [['longitude', 'latitude'], 'string', 'max' => 25]
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        foreach ($this->counters as $counter ) {

         \app\components\Search::Indexing($counter);

        }


    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('address','Id'),// 'ID',
            'counter_id' => Yii::t('address','Counter Id'),// 'Counter ID',
            'region_id' => Yii::t('address','Region Id'),//'№ Региона',
            'exploitation'=>Yii::t('address','Exploitation'),//'Сдан в эксплуатацию',
            'street' => Yii::t('address','Street'),//'Улица',
            'house' => Yii::t('address','House'),//'Дом',
            'longitude'=>Yii::t('address','Longitude'),///'Широта',
            'latitude'=>Yii::t('address','Latitude'),//'Долгота',
            'status' => Yii::t('address','Status'),//'Статус',
            'city' => Yii::t('address','City'),
            'flatindications' => Yii::t('address','Flat indications'),
        ];
    }

    public function getFullAddress() {

        if(!empty($this->street) and !empty($this->house)) {
            return $this->street . ' ' . $this->house;
        }else{
            return '';
        }
    }

    public function getFullAddressWithCity() {



        return  $this->getRegion()->one()->name .', ул. '.$this->street.' '.$this->house;
       // return  $this->street.' '.$this->house;

    }

    public function getRegion() {
        return $this->hasOne(Region::className(), array('id' => 'region_id'));
    }

    public function getCounters() {
        return $this->hasMany(Counter::className(), array('geo_location_id' => 'id'));
    }

    public function getHouseIndicationsForPeriod() {


        $whereInstalled = Yii::$app->request->get('where_installed', 0);

        $flatCounters = UserCounters::find()->where('geo_location_id =:geo_location_id', [':geo_location_id' => $this->id])
            ->andWhere('counters.serial_number IS NOT NULL');


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

        return $indications;
    }

    public function getHouseCount() {


        $whereInstalled = Yii::$app->request->get('where_installed', 0);

        $flatCounters = Counter::find()->where('geo_location_id =:geo_location_id', [':geo_location_id' => $this->id])
            ->andWhere('counters.serial_number IS NOT NULL');


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

        $query = Indication::find()->where('user_counter_id IN('.$counterList->createCommand()->getRawSql().')');

        if ($beginDate) {
            $query->andWhere('indications.created_at >= DATE(:beginDate)', [':beginDate' => $beginDate ]);
        }

        if ($endDate) {
            $query->andWhere('indications.created_at <= DATE(:endDate)', [':endDate' => $endDate ]);
        }

        $result = $query->orderBy('indications.created_at')->limit($limit)->all();


        return $this->sum2($result);
    }

    public function getLastIndications($beginDate,$endDate,$limit = 1) {

        $counterList = CounterQuery::counterIdQueryByRole($this->id);

        $query = Indication::find()->where('counter_id IN('.$counterList->createCommand()->getRawSql().')');

        if ($beginDate) {
            $query->andWhere('indication.created_at >= DATE(:beginDate)', [':beginDate' => $beginDate ]);
        }

        if ($endDate) {
            $query->andWhere('indication.created_at <= DATE(:endDate)', [':endDate' => $endDate ]);
        }

        $result = $query->orderBy(['indication.created_at' => SORT_DESC])->limit($limit)->all();

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
