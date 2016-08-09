<?php

namespace app\models;

use Yii;

use app\models\Rmodule;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class RmoduleSearch extends Rmodule
{

    public $pagination=['pageSize' => 15,];


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'modem_id', 'counter_id', 'serial_number', 'last_impulse', 'battery_level', 'timecode', 'geo_location_id', 'is_ignore_alert', 'update_interval','month_update', 'updated_at', 'created_at','month_update_type'], 'safe'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params) {
        $query = Rmodule::find();


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>$this->pagination,
        ]);

        $this->load($params);

        $query
            ->andFilterWhere(['modem_id' => $this->modem_id])
            ->andFilterWhere(['counter_id' => $this->counter_id])
            ->andFilterWhere(['battery_level' => $this->counter_id])
        ;

        return $dataProvider;
    }

}
