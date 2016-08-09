<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rmodules".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $modem_id
 * @property integer $counter_id
 * @property integer $serial_number
 * @property integer $last_impulse
 * @property integer $battery_level
 * @property integer $timecode
 * @property integer $geo_location_id
 * @property integer $is_ignore_alert
 * @property integer $update_interval
 * @property string $month_update
 * @property string $month_update_type
 * @property string $updated_at
 * @property string $created_at
 */
class Rmodule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rmodules';
    }

    public function IsIgnoreAlertsList()
    {
        return["1"=>Yii::t('app','Yes')];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'modem_id', 'counter_id', 'serial_number', 'last_impulse', 'battery_level', 'timecode', 'geo_location_id', 'is_ignore_alert', 'update_interval'], 'integer'],
            ['counter_id','isUnique'],
            ['modem_id','exist', 'targetClass' => Modem::className(), 'targetAttribute' => 'serial_number'],
            [['month_update', 'updated_at', 'created_at'], 'safe'],
            [['month_update_type'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => Yii::t('counter','User'),//'Пользователь',
            'modem_id' => Yii::t('counter','modem_number'),//'№ Модема',
            'counter_id' => Yii::t('counter','counter_number'),//''№ Cчетчика',
            'serial_number' =>  Yii::t('counter','rmodule_number'),//'№ Радиомодуля',
            'last_impulse' => Yii::t('counter','last_impulse'),//'Last Impulse',
            'battery_level' => Yii::t('counter','battery_level'),//'Заряд Батареи',
            'timecode' => Yii::t('counter','timecode'),
            'geo_location_id' => Yii::t('counter','address'),//'Адрес',
            'is_ignore_alert' => Yii::t('counter','is_ignore_alert'),//'Отключить предупреждения',
            'update_interval' => Yii::t('counter','update_interval'),//'Частота обновления( в часах)',
            'month_update' =>Yii::t('counter','month_update'),// 'Месячное обновление',
            'month_update_type' =>Yii::t('counter','month_update_type'),// 'Month Update Type',
            'updated_at' => Yii::t('counter','updated_at'),//'Дата и время',
            'created_at' => Yii::t('counter','created_at'),//'Created At', //1675,85
        ];
    }

    private function getCountMess()
    {



        if($c=Counter::findOne(['id'=>$this->counter_id])){

            $b=' Счетчик №'.$c->serial_number.'  привязан к радиомодулю №'.$c->rmodule_id;

        }
        return $b;

    }

    public function isCounterNotExist($counter_id){


        if( Rmodule::find()->where(['counter_id'=>$counter_id])->count()>0)
        {

            $this->addError('counter_id', $this->getCountMess());
            return false;

        }


    }

    public function isUnique($attribute,$params)

    {
        if ($this->isAttributeChanged($attribute,false)) {


            if ($this->isCounterNotExist($this->counter_id)) {

                return true;

            }
            else{
                return false;
            }

        }else{
            return true;
        }

    }


    public function getUser() {
        return $this->hasOne(User::className(), array('id' => 'user_id'));
    }

    public function getAddress() {
        return $this->hasOne(Address::className(), array('id' => 'geo_location_id'));
    }

    public function getCounter() {

        if(!empty($this->counter_id)){

            $counter = Counter::findOne(['id' => $this->counter_id]);

        }else{
            $counter = new Counter();
        }
        return $counter;
    }
}
