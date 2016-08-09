<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UserCounters;
use app\components\CounterQuery;
use app\components\Alerts;

/**
 * CounterAddressSearch represents the model behind the search form about `app\models\CounterAddress`.
 */
class UserCountersSearch extends UserCounters {
    

    public $realSerialNumberIsNull=false;
    public $nonDefinedCounter=false;//  если  нет  серийного номера или неопределен тип
    public $distinct=false;
    public $pagination=['pageSize' => 15,];
    public $beginDate;
    public $endDate;
    public $geoLocationId;
    public $type;
    public $builtIn=false;
    public $fulladdress;
    public $exploitation;
            
    public $tamper;
    public $disconnect;
    public $lowBatteryLevel;
    
    public $region;
    public $region_id;
    /**
     * @inheritdoc
     */
    
    public function __construct($config = array()) {
        
        $this->beginDate = Yii::$app->params['beginDate'];
        $this->endDate = date('Y-M-d');
        parent::__construct($config);
    }
    
        public function attributeLabels() {
        return [
   
            'exploitation'=>'Сдан в эксплуатацию',
       
        ];
    }

    public function rules() {
        return [
            
            [['initial_indications',
                'last_indications',
                'flat',
                'geo_location_id',
                'update_interval',
                'battery_level',
                'account',
                'fulladdress',
                'counter_model',
                'user_modem_id',
                'serial_number',
                'real_serial_number',
                'tamper_detect',
                'user_id',
                'fullname',
                'user_type',
                'updated_at',
                'created_at',
                'where_installed',
                'type',
                'tamper_detect_key'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = UserCounters::find()
                ->joinWith('user')
                ->joinWith('address')
                ->distinct($this->distinct);
        
        $alerts = new Alerts('');
        
        if($this->realSerialNumberIsNull)
            {
            $query->where('real_serial_number IS NULL');
            }
            else
            {
            $query->where('real_serial_number IS NOT NULL');  
            }
            
            
        if($this->nonDefinedCounter)
            {
            $query->where('real_serial_number IS NULL');
            $query->orWhere(['type'=>'']);
            }
            else
            {
            $query->where('real_serial_number IS NOT NULL');
            
            }
            
            
        //$queryWithCounters = CounterQuery::counterQueryByRoleAndAddress($query);
        
        

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>$this->pagination,
        ]);

        $this->load($params);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        if (CounterQuery::isRole('admin')) {
             $query->andFilterWhere(['user_counters.type' => $this->type]);
        }

        if (CounterQuery::isRole('gasWatcher')) {
          $query->andWhere(['user_counters.type'=>'gas']); 
        }
        
        if (CounterQuery::isRole('waterWatcher')) {
           $query->andWhere(['user_counters.type'=>'water']); 
        }

        if (CounterQuery::isRole('regionWatcher')) {

            $user = User::find()->where(['id' => Yii::$app->user->id])->one();
            $query->andWhere('counter_address.region_id =:region_id', [':region_id' => $user->geo_location_id]);
           
        }


        if (CounterQuery::isRole('user')) {
            $query->andWhere('user_counters.user_id =:user_id', [':user_id' => Yii::$app->user->id]);
        }
        
        if ($this->region_id != 0) {
            $query->andFilterWhere(['counter_address.region_id' => $this->region_id]);
        }
        
        if ($this->leak != 0) {
            $query->andFilterWhere(['user_counters.leak' => '1']);
                    
        }

        if ($this->geoLocationId != 0) {
            $query->andFilterWhere(['user_counters.geo_location_id' => $this->geoLocationId]);

        }
        if ($this->type!= 0) {
            $query->andFilterWhere(['user_counters.type' => $this->type]);

        }
        
        if ($this->builtIn!= 0) {
            $query->joinWith('modem');
            $query->andFilterWhere(['user_modems.type' => 'built-in']);
                    
        }

        if ($this->tamper != 0) {
            $query->andWhere('user_counters.tamper_detect IS NOT NULL');
                    
        }

        if ($this->magnet != 0) {
            $query->andFilterWhere(['user_counters.magnet' => '1']);
                     
        }

        if ($this->lowBatteryLevel != 0) {
            $query->andWhere('user_counters.battery_level<=:batteryLevel', [':batteryLevel' => '5']);
                 
        }

        if ($this->disconnect != 0) {

            $string = null;
            foreach ($query->all() as $counter) {
                if ($alerts->isDisconnect($counter)) {
                    $string[] = $counter->id;
                }
            }
            $query->andFilterWhere(['in', 'user_counters.id', $string])->andWhere('user_counters.is_ignore_alert !=1');
                 
        }
        
    
        
        
        
        $query->filterWhere(['user_counters.serial_number' => $this->serial_number])
                ->andFilterWhere(['user_modem_id' => $this->user_modem_id])
                ->andFilterWhere(['account' => $this->account])
                //->andFilterWhere(['real_serial_number' => $this->real_serial_number])
                ->andFilterWhere(['flat' => $this->flat])
                ->andFilterWhere(['counter_address.exploitation' =>$this->exploitation])
                //->andFilterWhere(['type' => $this->type])
                ->andFilterWhere(['updated_at' => $this->updated_at])
                ->andFilterWhere(['created_at' => $this->created_at])
                ->andFilterWhere(['in', 'user_counters.id', $this->id]) 
                ->andFilterWhere(['user_counters.geo_location_id' => $this->geo_location_id])
                ->andFilterWhere(
    ['or',
    ['and',['like','counter_address.street',$this->fulladdress],['like','counter_address.house',$this->fulladdress]],
    ['or',['like','counter_address.street',$this->fulladdress],['like','counter_address.house',$this->fulladdress]]
                        ])
                ;
                
        return $dataProvider;
    }

}
