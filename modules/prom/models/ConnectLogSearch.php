<?php

namespace app\models;

use Yii;
use app\models\Modem;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class ConnectLogSearch extends Modem
{



    public $distinct=false;
    public $pagination=['pageSize' => 15,];
    public $alertType;


    public function rules()
    {
        return [
            [
                [
                'user_id',
                'signal_level',
                'geo_location_id',
                'serial_number',
                'updated_at',
                'created_at',
                'last_invoice_request',
                'phone_number',
                'invoice_request',
                'everyday_update_interval'
                ],'safe'
            ]
        ];
    }



    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'serial_number' => '№ модема',
            'phone_number' => 'Телефон',
            'last_invoice_request' => 'Баланс',
            'invoice_request' => 'Код запроса ',
            'signal_level' => 'Уровень сигнала',
            'geo_location_id'=>'Адресс',
            'updated_at' => 'Данные обновлены ',
            'created_at' => 'Установлен ',
            'type' => 'Тип модема',
            'everyday_update_interval'=>'Обязательное ежедневное обновление',
            'alert_datacode1' => 'Alert Datacode1',
            'alert_datacode2' => 'Alert Datacode2',
            'alert_datacode3' => 'Alert Datacode3',
            'alert_datacode4' => 'Alert Datacode4',
            'alert_type1' => 'Alert Type1',
            'alert_type2' => 'Alert Type2',
            'alert_type3' => 'Alert Type3',
            'alert_type4' => 'Alert Type4',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];


    }

    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params) {
        $query = Modem::find()
            ->joinWith('counters',true)
            ->joinWith('address',true)
            ->joinWith('alerts',true)
            ->distinct($this->distinct);

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

        if($this->alertType){

                $query->andWhere('modems.serial_number = alerts_list.serial_number and alerts_list.type=:type',[':type'=>$this->alertType]);

        }

        $query
            ->filterWhere(['phone_number' => $this->phone_number])
            ->andFilterWhere(['modems.serial_number' => $this->serial_number])
            ->andFilterWhere(['modems.geo_location_id' => $this->geo_location_id])
            ->andFilterWhere(['created_at' => $this->created_at])
            ->andFilterWhere(['signal_level' => $this->signal_level])
            ->andFilterWhere(['updated_at' => $this->updated_at]);


        return $dataProvider;
    }



}
