<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "MomentData".
 *
 * @property integer $id
 * @property integer $all_id
 * @property integer $branch_id
 * @property double $pabs
 * @property double $pabs2
 * @property double $paverage
 * @property double $ppkrit
 * @property double $pdelta
 * @property double $dsu
 * @property double $dtube
 * @property double $tabs
 * @property double $taverage
 * @property double $tpkrit
 * @property double $vconsum_sc
 * @property double $vconsum_rc
 * @property double $vday_sc
 * @property double $vprevday_sc
 * @property double $vhour_sc
 * @property double $vprevhour_sc
 * @property double $vbm_sc
 * @property double $vemerg_day_sc
 * @property double $vemerg_day_rc
 * @property double $vadd_rc
 * @property double $time_nonsanction_const_day
 * @property double $time_measurm_emerg_day
 * @property double $time_metod_emerg_day
 * @property double $time_nosuply_emerg_day
 * @property double $counter_data
 * @property double $s_q_r_t
 * @property double $beta
 * @property double $kappa
 * @property double $teta0
 * @property double $teta1
 * @property double $epsilon
 * @property double $mu
 * @property double $kp
 * @property double $ksh
 * @property double $ktsu
 * @property double $kttr
 * @property double $kr
 * @property double $k
 * @property double $kist
 * @property double $b1
 * @property double $b2
 * @property double $b0
 * @property double $f
 * @property double $z_rc
 * @property double $z_sc
 * @property double $re
 */
class MomentData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'MomentData';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['all_id', 'branch_id'], 'integer'],
            [['pabs', 'pabs2', 'paverage', 'ppkrit', 'pdelta', 'dsu', 'dtube', 'tabs', 'taverage', 'tpkrit', 'vconsum_sc', 'vconsum_rc', 'vday_sc', 'vprevday_sc', 'vhour_sc', 'vprevhour_sc', 'vbm_sc', 'vemerg_day_sc', 'vemerg_day_rc', 'vadd_rc', 'time_nonsanction_const_day', 'time_measurm_emerg_day', 'time_metod_emerg_day', 'time_nosuply_emerg_day', 'counter_data', 's_q_r_t', 'beta', 'kappa', 'teta0', 'teta1', 'epsilon', 'mu', 'kp', 'ksh', 'ktsu', 'kttr', 'kr', 'k', 'kist', 'b1', 'b2', 'b0', 'f', 'z_rc', 'z_sc', 're'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */



    public function getName(){
        return $this->hasOne(Name::className(), array('all_id' => 'all_id'));
    }

    public function getStaticDataGeneral(){
        return $this->hasOne(StaticDataGeneral::className(), array('all_id' => 'all_id'));
    }

    public function getStaticDataSensor(){
        return $this->hasOne(StaticDataSensor::className(), array('all_id' => 'all_id'));
    }



    public function attributeLabels()
    {
        return [
            'created_at'=>Yii::t('momentData','Updated at'),//'время обновления',
            //'vbm_sc' => 'Расход с начала наблюдений с.у. , м3',
            'vday_sc' => Yii::t('momentData','Consumption per day s.c. ,m<sup>3</sup>'),//'Расход за день с.у. ,м3',
            'vhour_sc' => Yii::t('momentData','Consumption per hour s.c. ,m<sup>3</sup>'),//'Расход за час с.у. ,м3',
            'vprevday_sc' => Yii::t('momentData','Consumption over the past day s.c. ,m<sup>3</sup>'),//'Расход за прошлый день с.у. ,м3',
            'vprevhour_sc' => Yii::t('momentData','Consumption over the past hour s.c. ,m<sup>3</sup>'),//'Расход за прошлый час с.у. ,м3',
            'pabs' => Yii::t('momentData','Pressure, kgf / m<sup>2</sup>'),//'Давление ,кгс/м2',
            'tabs' => Yii::t('momentData','Temperature C<sup>o</sup>'),//'Температура , град. Целс.',

            'paverage' => Yii::t('momentData','Average pressure, kgf / m<sup>2</sup>'),//'Cреднее Давление ,кгс/м2',
            'taverage' => Yii::t('momentData','Average temperature C<sup>o</sup>'),//'Средняя температура',
            'ppkrit' => Yii::t('momentData','Pseudocritical pressure, kgf / m<sup>2</sup>'),//'Псевдокритическое давление ,кгс/м2',
            'tpkrit' => Yii::t('momentData','Pseudocritical temperature C<sup>o</sup>'),//'Псевдокритическая температура , град. Целс.',

            'pdelta' => Yii::t('momentData','Differential pressure, kgf / m<sup>2</sup>'),//'Перепад давления ,кгс/м2',
            'dsu' => Yii::t('momentData','Diameter ND , mm'),//'Диаметр СУ ,мм',
            'dtube' =>Yii::t('momentData','Diameter Tube, mm'),// 'Диаметр Трубы ,мм',

            'vconsum_sc' => Yii::t('momentData','Volume consumption s.c.'),// 'Объемный расход с.у.',
            'vconsum_rc' => Yii::t('momentData','Volume consumption r.c.'),// 'Объемный расход р.у',




           /* 'vemerg_day_sc' => 'Vemerg Day Sc',
            'vemerg_day_rc' => 'Vemerg Day Rc',
            'vadd_rc' => 'Vadd Rc',
            'time_nonsanction_const_day' => 'Time Nonsanction Const Day',
            'time_measurm_emerg_day' => 'Time Measurm Emerg Day',
            'time_metod_emerg_day' => 'Time Metod Emerg Day',
            'time_nosuply_emerg_day' => 'Time Nosuply Emerg Day',

            's_q_r_t' => 'S Q R T',
            'beta' => 'Beta',
            'kappa' => 'Kappa',
            'teta0' => 'Teta0',
            'teta1' => 'Teta1',
            'epsilon' => 'Epsilon',
            'mu' => 'Mu',
            'kp' => 'Kp',
            'ksh' => 'Ksh',
            'ktsu' => 'Ktsu',
            'kttr' => 'Kttr',
            'kr' => 'Kr',
            'k' => 'K',
            'kist' => 'Kist',
            'b1' => 'B1',
            'b2' => 'B2',
            'b0' => 'B0',
            'f' => 'F',
            'z_rc' => 'Z Rc',
            'z_sc' => 'Z Sc',
            're' => 'Re',*/
            // 'counter_data' => 'Показания счетчика',


            //'id' => 'ID',
            //'all_id' => 'All ID',
            //'branch_id' => 'Branch ID',

            //'pabs2' => 'Pabs2',
        ];
    }
}
