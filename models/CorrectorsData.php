<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "correctors_data".
 *
 * @property integer $id
 * @property integer $indication_id
 * @property integer $temperature
 * @property integer $pressure
 * @property double $uncorrected_indications
 * @property resource $raw_packet
 */
class CorrectorsData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'correctors_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['indication_id', 'temperature', 'pressure'], 'integer'],
            [['uncorrected_indications'], 'number'],
            [['raw_packet'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'indication_id' => 'Indication ID',
            'temperature' => 'Temperature',
            'pressure' => 'Pressure',
            'uncorrected_indications' => 'Uncorrected Indications',
            'raw_packet' => 'Raw Packet',
        ];
    }
}
