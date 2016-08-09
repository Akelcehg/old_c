<?php

namespace app\modules\metrix\models;

use Yii;

/**
 * This is the model class for table "metrix_modem_status".
 *
 * @property integer $id
 * @property integer $modem_id
 * @property integer $balance
 * @property string $phone
 * @property string $invoice
 * @property string $time_on_line
 * @property string $status
 */
class MetrixModemStatus extends \yii\db\ActiveRecord
{

    const MODEM_STATUS_ON_LINE = 'On-Line';
    const MODEM_STATUS_BUSY  = 'Busy';
    const MODEM_STATUS_SLEEP  = 'Sleep';
    const MODEM_STATUS_DISCONNECT  = 'Disconnect';

    public static function getModemStatusList(){
        return [
            self::MODEM_STATUS_ON_LINE => 'На связи',
            self::MODEM_STATUS_SLEEP => 'В ожидании',
            self::MODEM_STATUS_BUSY => 'Занят',
            self::MODEM_STATUS_DISCONNECT => 'Нет связи',

        ];
    }

    public function getModemStatusText(){
        $modemStatusList = self::getModemStatusList();
        if(isset($modemStatusList[$this->status])){
            return $modemStatusList[$this->status];
        }else {
            return $this->status;
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'metrix_modem_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'modem_id', 'balance'], 'integer'],
            [['invoice', 'status'], 'string'],
            [['time_on_line'], 'safe'],
            [['phone'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'modem_id' => 'Modem ID',
            'balance' => 'Balance',
            'phone' => 'Phone',
            'invoice' => 'Invoice',
            'time_on_line' => 'Time On Line',
            'status' => 'Status',
        ];
    }
}
