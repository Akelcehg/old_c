<?php

namespace app\modules\metrix\models;

use Yii;

/**
 * This is the model class for table "metrix_sim_card".
 *
 * @property integer $id
 * @property integer $modem_id
 * @property integer $contract_day
 * @property integer $tarif
 * @property string $packet
 * @property string $request_forced_payment
 * @property string $request_balance
 * @property string $request_get_packet
 */
class MetrixSimCard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'metrix_sim_card';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'contract_day', 'tarif'], 'integer'],
            [['packet'], 'string'],
            [['request_forced_payment', 'request_balance', 'request_get_packet'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'modem_id' => '№ Модема',
            'contract_day' => 'День списания со счета',
            'tarif' => 'Тариф',
            'packet' => 'Описание пакета',
            'request_forced_payment' => 'Запрос принудительного списания ',
            'request_balance' => 'Запрос баланса',
            'request_get_packet' => 'Запрос названия пакета',

        ];
    }
}
