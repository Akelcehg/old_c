<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "StaticDataSensor".
 *
 * @property integer $id
 * @property integer $all_id
 * @property integer $branch_id
 * @property double $q_min
 * @property double $pmax
 * @property double $qstop
 * @property double $max_mesurm_lim_q
 * @property double $max_mesurm_lim_p
 * @property double $min_mesurm_lim_q
 * @property double $min_mesurm_lim_p
 * @property double $max_mesurm_lim_t
 * @property double $min_mesurm_lim_t
 * @property string $counter_name
 */
class StaticDataSensor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'StaticDataSensor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['all_id', 'branch_id'], 'integer'],
            [['q_min', 'pmax', 'qstop', 'max_mesurm_lim_q', 'max_mesurm_lim_p', 'min_mesurm_lim_q', 'min_mesurm_lim_p', 'max_mesurm_lim_t', 'min_mesurm_lim_t'], 'number'],
            [['counter_name'], 'string', 'max' => 255]
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
            'q_min' => 'Q Min',
            'pmax' => 'Pmax',
            'qstop' => 'Qstop',
            'max_mesurm_lim_q' => 'Max Mesurm Lim Q',
            'max_mesurm_lim_p' => 'Max Mesurm Lim P',
            'min_mesurm_lim_q' => 'Min Mesurm Lim Q',
            'min_mesurm_lim_p' => 'Min Mesurm Lim P',
            'max_mesurm_lim_t' => 'Max Mesurm Lim T',
            'min_mesurm_lim_t' => 'Min Mesurm Lim T',
            'counter_name' => 'Counter Name',
        ];
    }
}
