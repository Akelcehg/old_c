<?php

namespace app\models;

use Yii;
use app\models\Modem;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class CommandConveyorSearch extends CommandConveyor
{

    public $distinct=false;
    public $pagination=['pageSize' => 15,];



    public function rules()
    {

                 return [
                     [
                         [
                             'command',
                             'modem_id',
                             'command_type',
                             'status',
                             'created_at',
                             'pending_at',
                             'disabled_at'
                         ],
                         'safe'
                     ],
                 ];


    }



    public function attributeLabels()
    {
        return [

        ];


    }

    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return CommandConveyor::scenarios();
    }

    public function search($params) {
        $query = CommandConveyor::find();

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

        //        if (!empty($phone)) $modems->andWhere(['phone_number' => $phone]);
        //if (!empty($serial)) $modems->andWhere(['serial_number' => $serial]);

        $query
            ->filterWhere(['modem_id' => $this->modem_id])
            ->andFilterWhere(['command' => $this->command])
            ->andFilterWhere(['command_type' => $this->command_type])
            ->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['created_at' => $this->created_at])
            ->andFilterWhere(['pending_at' => $this->pending_at])
            ->andFilterWhere(['disabled_at' => $this->disabled_at]);


        return $dataProvider;
    }



}
