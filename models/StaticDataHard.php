<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "StaticDataHard".
 *
 * @property integer $id
 * @property integer $all_id
 * @property integer $branch_id
 * @property double $density
 * @property double $mol_co2
 * @property double $mol_n2
 * @property double $d_tube
 * @property double $d_sug_device
 * @property double $atm_pressure
 * @property double $control_interval
 * @property double $sharpness
 * @property double $difmanometr_limit
 * @property double $radius_diafr
 * @property double $otsechka
 */
class StaticDataHard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'StaticDataHard';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['all_id', 'branch_id'], 'integer'],
            [['density', 'mol_co2', 'mol_n2', 'd_tube', 'd_sug_device', 'atm_pressure', 'control_interval', 'sharpness', 'difmanometr_limit', 'radius_diafr', 'otsechka'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'all_id' => 'All ID',
            'branch_id' => 'Branch ID',
            'density' => 'Density',
            'mol_co2' => 'Mol Co2',
            'mol_n2' => 'Mol N2',
            'd_tube' => 'D Tube',
            'd_sug_device' => 'D Sug Device',
            'atm_pressure' => 'Atm Pressure',
            'control_interval' => 'Control Interval',
            'sharpness' => 'Sharpness',
            'difmanometr_limit' => 'Difmanometr Limit',
            'radius_diafr' => 'Radius Diafr',
            'otsechka' => 'Otsechka',
        ];
    }
}
