<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Counter;
use app\components\CounterQuery;
use app\components\Alerts;

/**
 * CounterAddressSearch represents the model behind the search form about `app\models\CounterAddress`.
 */
class CounterSearch extends Counter {
    

    public $serialNumberIsNull=false;
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

    public $region;
    public $region_id;

    public $serial;

    public $flatindications;
    public $city;
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


        ];
    }

    public function rules() {
        return [
            
            [['counter_model','modem_id','rmodule_id','serial_number','flat'], 'integer'],
            [['updated_at','created_at'],'date', 'format' => 'yyyy-M-d H:m:s','message'=>' неправильный формат времени'],


                [['initial_indications',
                'last_indications',

                'geo_location_id',


                'account',
                'fulladdress',




                'user_id',
                'fullname',
                'user_type',


                'type',

                'serial'
            ], 'safe'],
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
        $query = Counter::find()
                ->joinWith('user')
                ->joinWith('address')
                ->distinct($this->distinct);
        
        //$alerts = new Alerts('');
        
        if($this->serialNumberIsNull)
            {
            $query->where('serial_number IS NULL');
            }
            else
            {
            $query->where('serial_number IS NOT NULL');
            }
            
            
        if($this->nonDefinedCounter)
            {
            $query->where('counters.serial_number IS NULL');
            $query->orWhere(['type'=>'']);
            }
            else
            {
            $query->where('counters.serial_number IS NOT NULL');
            
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
             $query->andFilterWhere(['counters.type' => $this->type]);
        }

        if (CounterQuery::isRole('gasWatcher')) {
          $query->andWhere(['counters.type'=>'gas']);
        }
        
        if (CounterQuery::isRole('waterWatcher')) {
           $query->andWhere(['counters.type'=>'water']);
        }

        if (CounterQuery::isRole('regionWatcher')) {

            $user = User::find()->where(['id' => Yii::$app->user->id])->one();
            $query->andWhere('address.region_id =:region_id', [':region_id' => $user->geo_location_id]);

        }


        if (CounterQuery::isRole('user')) {
            $query->andWhere('counters.user_id =:user_id', [':user_id' => Yii::$app->user->id]);
        }
        
        if ($this->region_id != 0) {
            $query->andFilterWhere(['address.region_id' => $this->region_id]);
        }
        


        if ($this->geoLocationId != 0) {
            $query->andFilterWhere(['counters.geo_location_id' => $this->geoLocationId]);

        }
        if ($this->type!= 0) {
            $query->andFilterWhere(['counters.type' => $this->type]);

        }
        
        if ($this->builtIn!= 0) {
            $query->joinWith('modem');
            $query->andFilterWhere(['modems.type' => 'built-in']);
                    
        }


        
    
        
        
        
        $query->filterWhere(['counters.serial_number' => $this->serial_number])
                ->andFilterWhere(['modem_id' => $this->modem_id])
                ->andFilterWhere(['rmodule_id' => $this->rmodule_id])
                ->andFilterWhere(['account' => $this->account])
                //->andFilterWhere(['real_serial_number' => $this->real_serial_number])
                ->andFilterWhere(['flat' => $this->flat])
                ->andFilterWhere(['address.exploitation' =>$this->exploitation])
                ->andFilterWhere(['counters.user_type' => $this->user_type])
                ->andFilterWhere(['updated_at' => $this->updated_at])
                ->andFilterWhere(['created_at' => $this->created_at])
                ->andFilterWhere(['in', 'counters.id', $this->id])
                ->andFilterWhere(['in', 'counters.serial_number', $this->serial])
                ->andFilterWhere(['counters.geo_location_id' => $this->geo_location_id])
                ->andFilterWhere(
    ['or',
    ['and',['like','address.street',$this->fulladdress],['like','address.house',$this->fulladdress]],
    ['or',['like','address.street',$this->fulladdress],['like','address.house',$this->fulladdress]]
                        ])
                ;
                
        return $dataProvider;
    }

}
