<?php

namespace app\models;

use Yii;
use app\models\AlertsList;
use yii\data\ActiveDataProvider;
use yii\base\Model;

/**
 * This is the model class for table "alerts_list".
 *
 * @property integer $id
 * @property string $serial_number
 * @property string $type
 * @property string $created_at
 * @property string $status
 */
class AlertsListSearch extends AlertsList
{
    public $user_modem_id=null;
    public $pagination=['pageSize' => 15,];

    public function rules() {
        return [
            [['status','serial_number','type','device_type','created_at','user_modem_id'], 'safe']
        ];
    }

    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params) {
        $query = AlertsList::find()->joinWith('counter')->joinWith('counters')->orderBy(['created_at' => SORT_DESC]);

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



        $query->filterWhere(['alerts_list.status' => $this->status])
            ->andFilterWhere(['alerts_list.serial_number' => $this->serial_number])
            ->andFilterWhere(['user_counters.user_modem_id' => $this->user_modem_id])
            //->andFilterWhere(['alerts_list.type'=>$this->alert_type])
            ->andFilterWhere(['counters.type'=>$this->type])
            ->andFilterWhere(['alerts_list.device_type' => $this->device_type])
            ->andFilterWhere(['alerts_list.created_at' => $this->created_at]);


        return $dataProvider;
    }

}
