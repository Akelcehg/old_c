<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "prom_info".
 *
 * @property integer $id
 * @property string $uegg_ps
 * @property string $np
 * @property string $number_ca
 * @property string $company
 * @property string $address
 * @property string $contract
 */
class PromInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'prom_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uegg_ps', 'np', 'number_ca', 'company', 'address', 'contract'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uegg_ps' => 'Uegg Ps',
            'np' => 'Np',
            'number_ca' => 'Number Ca',
            'company' => 'Company',
            'address' => 'Address',
            'contract' => 'Contract',
        ];
    }
}
