<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sim_card".
 *
 * @property integer $id
 * @property integer $modem_id
 * @property integer $contract_day
 * @property integer $tarif
 * @property string $packet
 * @property string $request_forced_payment
 * @property string $request_balance
 */
class SimCard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sim_card';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['id'], 'required'],
            [['id', 'modem_id', 'contract_day', 'tarif'], 'integer'],
            [['packet'], 'string'],
            [['request_forced_payment', 'request_balance','request_get_packet',], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('simcard','Id'),//
            'modem_id' => Yii::t('simcard','Modem number'),//'№ Модема',
            'contract_day' => Yii::t('simcard','Contract day'),//'День списания со счета',
            'tarif' => Yii::t('simcard','Rate'),//'Тариф',
            'packet' => Yii::t('simcard','Description package'),//'Описание пакета',
            'request_forced_payment' => Yii::t('simcard','Request for forced debiting'),//'Запрос принудительного списания ',
            'request_balance' => Yii::t('simcard','Request for balance'),//'Запрос баланса',
            'request_get_packet' => Yii::t('simcard','Request package name'),//'Запрос названия пакета',

        ];
    }
}
