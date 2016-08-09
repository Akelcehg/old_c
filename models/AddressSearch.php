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
class AddressSearch extends Address
{

    public $alerts;
    public $endDate;
    public $beginDate;
    public $region;
    public $user_type;
    public $city;
    public $type = null;
    public $status = null;
    public $serialNumberIsNull = false;
    public $isCounter=false;

    /**
     * @inheritdoc
     */
    public function __construct($config = array())
    {
        //$this->alerts = new Alerts('');
        $this->beginDate = Yii::$app->params['beginDate'];
        $this->endDate = date('Y-M-d');
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['id', 'region_id'], 'integer'],
            [
                ['street',
                    'house',
                    'longitude',
                    'latitude',
                    'city',
                    'type',
                    'region',
                    'endDate',
                    'beginDate',
                    'status'
                ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = Address::find();
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


        $queryWithCounters->andFilterWhere(['in', 'address.status', $this->status]);

        if ($this->isCounter===true) {


            if ($this->serialNumberIsNull) {
                $query->andWhere('counters.serial_number IS NULL');
            } else {
                $query->andWhere('counters.serial_number IS NOT NULL');
            }


            if ($this->user_type) {
                $queryWithCounters->andFilterWhere(['counters.user_type' => $this->user_type]);
            }
            $user = new User();
            if ($user->is('gasWatcher')) {
                $queryWithCounters->andWhere(['counters.type' => 'gas']);
            }

            if ($user->is('waterWatcher')) {
                $queryWithCounters->andWhere(['counters.type' => 'water']);
            }

            if (User::is('admin')) {
                $queryWithCounters->andFilterWhere(['counters.type' => $this->type]);
            }
        }

        if ($this->id != 0) {
            $queryWithCounters->andFilterWhere(['address.id' => $this->id]);
        }

        if ($this->region_id != 0) {
            $queryWithCounters->andFilterWhere(['region_id' => $this->region_id]);
        }


        $queryWithCounters->andFilterWhere(['like', 'street', $this->street])
            ->andFilterWhere(['like', 'house', $this->house])
            ->andFilterWhere(['like', 'longitude', $this->longitude])
            ->andFilterWhere(['like', 'latitude', $this->latitude]);
        //print_r($dataProvider);
        return $dataProvider;
    }

}
