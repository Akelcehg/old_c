<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CounterAddress;
use app\components\CounterQuery;
use app\components\Alerts;

/**
 * CounterAddressSearch represents the model behind the search form about `app\models\CounterAddress`.
 */
class CounterAddressSearch extends CounterAddress {

    public $alerts;
    public $endDate;
    public $beginDate;
    public $region;
    public $user_type;
    public $city;
    public $leak;
    public $type = null;
    public $tamper;
    public $lowBatteryLevel;
    public $magnet;
    public $disconnect;
    public $status = null;
    public $realSerialNumberIsNull = false;
    public $flatindications;

    /**
     * @inheritdoc
     */
    public function __construct($config = array()) {
        $this->alerts = new Alerts('');
        $this->beginDate = Yii::$app->params['beginDate'];
        $this->endDate = date('Y-M-d');
        parent::__construct($config);
    }

    public function rules() {
        return [
            [['id', 'region_id'], 'integer'],
            [
                [ 'street',
                    'house',
                    'longitude',
                    'latitude',
                    'city',
                    'type',
                    'region',
                    'endDate',
                    'beginDate',
                    'leak',
                    'tamper',
                    'lowBatteryLevel',
                    'magnet',
                    'disconnect',
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
        $query = CounterAddress::find();
        $queryWithCounters = CounterQuery::counterQueryByRoleAndAddress($query);

        $dataProvider = new ActiveDataProvider([
            'query' => $queryWithCounters,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


        $queryWithCounters->andFilterWhere(['counter_address.status' => $this->status]);



        if ($this->realSerialNumberIsNull) {
            $query->andWhere('user_counters.real_serial_number IS NULL');
        } else {
            $query->andWhere('user_counters.real_serial_number IS NOT NULL');
        }


        if ($this->user_type){
            $queryWithCounters->andFilterWhere(['user_counters.user_type' => $this->user_type]);
        }

        if ($this->id != 0) {
            $queryWithCounters->andFilterWhere(['counter_address.id' => $this->id]);
        }

        if ($this->region_id != 0) {
            $queryWithCounters->andFilterWhere(['region_id' => $this->region_id]);
        }

        $user = new User();

        if ($user->is('gasWatcher')) {
          $queryWithCounters->andWhere(['user_counters.type'=>'gas']); 
        }

        if ($user->is('waterWatcher')) {
           $queryWithCounters->andWhere(['user_counters.type'=>'water']); 
        }


        if ($this->leak != 0) {
            
        }
        
        if (CounterQuery::isRole('admin')) {
        
        $queryWithCounters->andFilterWhere(['user_counters.type' => $this->type]);
        }

        if ($this->tamper != 0) {
            
        }

        if ($this->magnet != 0) {
            
        }

        if ($this->lowBatteryLevel != 0) {
            
        }



        if ($this->disconnect != 0) {
            
        }




        $queryWithCounters->andFilterWhere(['like', 'street', $this->street])
                ->andFilterWhere(['like', 'house', $this->house])
                ->andFilterWhere(['like', 'longitude', $this->longitude])
                ->andFilterWhere(['like', 'latitude', $this->latitude]);

        return $dataProvider;
    }

}
