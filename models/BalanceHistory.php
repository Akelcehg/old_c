<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "balance_history".
 *
 * @property integer $id
 * @property integer $modem_id
 * @property double $balance
 */
class BalanceHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'balance_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
           // [['id'], 'required'],
            [['id', 'modem_id'], 'integer'],
            [['balance'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' =>Yii::t('balanceHistory','Id'),// 'ID',
            'modem_id' => Yii::t('balanceHistory','Modem ID'),
            'balance' => Yii::t('balanceHistory','Balance'),//'Баланс',
            'date'=>Yii::t('balanceHistory','Time of check'),//' время проверки'
        ];
    }
}
