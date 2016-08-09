<?php

namespace app\models;
use Yii;

/**
 * This is the model class for table "survey_data".
 *
 * @property integer $id
 * @property integer $address_id
 * @property string $install_place
 * @property string $install_replace
 * @property string $is_restricted_area
 * @property string $device_type
 * @property string $corrector_type
 * @property string $interface_converter_info
 * @property string $data_cable_info
 * @property string $supply_type
 * @property string $gsm_signal_level
 * @property string $service_company_phone
 * @property string $modem_mount_type
 * @property string $created_at
 * @property string $updated_at
 */
class SurveyData extends \yii\db\ActiveRecord
{




    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'survey_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['address_id'], 'integer'],
            ['install_place','in', 'range' => ['inside','outside']],
            ['install_replace','in', 'range' => ['new','replace']],
            ['status','in', 'range' => ['new','inwork','active','disabled']],
            ['device_type','in', 'range' => ['counter','corrector']],
            [['created_at', 'updated_at'], 'safe'],
            [['description'], 'string'],
            [['is_restricted_area', 'corrector_type', 'interface_converter_info', 'data_cable_info', 'supply_type', 'gsm_signal_level', 'service_company_phone', 'modem_mount_type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address_id' => 'Адрес',
            'install_place' => 'Место установки модема',
            'install_replace' => 'Новая установка или на место ранее установленного',
            'is_restricted_area' => 'Режимность объекта (документы для доступа)',
            'device_type' =>'Тип прибора учета газа', //Yii::t('surveyData','Device Type'),
            'corrector_type' => 'Тип корректора ( адрес и скорость )',
            'interface_converter_info' => 'Наличие и тип преобразователя интерфейсов',
            'data_cable_info' => 'Тип подключения информационного кабеля ( разъем , клемник ) и длина кабеля',
            'supply_type' => 'Тип подключения питания ( наличие 220В)',
            'gsm_signal_level' => ' Уровень сигнала антенны ( GSM замер )',
            'service_company_phone' => 'Телефон для связи с обслуживающей компанией',
            'modem_mount_type' => 'Способ крепления модема ( стяжки, двухсторонний скотч)',
            'status'=>'Статус',
            'description'=>'Заметки',
            'created_at' => 'Создано',
            'updated_at' => 'Изменено',
        ];
    }

    public function getAddress() {
        return $this->hasOne(Address::className(), array('id' => 'address_id'));
    }

    public function getFulladdress() {
        $model=$this->hasOne(Address::className(), array('id' => 'address_id'))->one();

        if(isset($model->street)){
            return $model->fulladdress;}
    }
}
