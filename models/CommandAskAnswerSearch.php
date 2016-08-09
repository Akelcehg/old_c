<?php

namespace app\models;

use Yii;
use app\models\Modem;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class CommandAskAnswerSearch extends CommandAskAnswer
{

    public $distinct=false;
    public $pagination=['pageSize' => 15,];



    public function rules()
    {

                 return [
                     [
                         [
                             'modem_id',
                             'command_conveyor_id',
                             'corrector_id',
                             'branch_id',
                             'command',
                             'ask',
                             'answer',
                             'created_at',
                             'answered_at'
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
        return CommandAskAnswer::scenarios();
    }

    public function search($params) {
        $query = CommandAskAnswer::find();

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
            ->andFilterWhere(['corrector_id' => $this->corrector_id])
            ->andFilterWhere(['command_conveyor_id' => $this->command_conveyor_id])
            ->andFilterWhere(['created_at' => $this->created_at])
            ->andFilterWhere(['answered_at' => $this->answered_at])
            ->andFilterWhere(['branch_id' => $this->branch_id]);


        return $dataProvider;
    }



}
