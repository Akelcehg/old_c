<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "counter_models".
 *
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property integer $rate
 */
class CounterModel extends \yii\db\ActiveRecord
{
    public static $availableRates = [
        '1'     => '1',
        '0.1'   => '0.1',
        '0.01'  => '0.01',
        '0.001' => '0.001',
    ];

    const COUNTER_TYPE_GAS = 'gas';
    const COUNTER_TYPE_WATER = 'water';
    
    
    public static function getCounterTypeList(){
        return [
            self::COUNTER_TYPE_GAS => Yii::t('common','gas'),//'газ',
            self::COUNTER_TYPE_WATER =>Yii::t('common','water'),// 'вода',
            
        ];
    }

    public function getCounterTypeText(){
        $counterTypeList = self::getCounterTypeList();
        if(isset($counterTypeList[$this->type])){
            return $counterTypeList[$this->type];
        }else {
            return $this->type;
        }
    }
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'counter_models';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'rate'], 'required'],
            [['type'], 'string'],
            [['rate'], 'number'],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('counter','counter_model_name'),// 'Название',
            'type' => Yii::t('counter','type'),//'Тип',
            'rate' => Yii::t('counter','impulse_rate'),//'Показатель импульсов',
        ];
    }
}
