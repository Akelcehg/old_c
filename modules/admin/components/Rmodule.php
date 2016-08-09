<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 11.01.16
 * Time: 11:11
 */

namespace app\modules\admin\components;
use app\models\RmoduleSearch;
use app\modules\admin\components\AdminComponent;
use Yii;
use app\components\Events;
use yii\web\HttpException;


class Rmodule extends  AdminComponent
{

    public function RmoduleList(){

        $this->model= new \app\models\Rmodule();
        $this->searchModel = new RmoduleSearch();
        $this->dataProvider=$this->searchModel->search(Yii::$app->request->queryParams);

        return true;

    }


    public function EditRmodule(){

        if (!($serial_number = Yii::$app->request->get('serial_number', FALSE))) {
            $arr = Yii::$app->request->post('Rmodule', FALSE);
            $serial_number = $arr['serial_number'];
        }

        $this->model =\app\models\Rmodule::find()->where(['serial_number' => $serial_number])->one();

        if(!$this->model)
        {
            throw new HttpException(404, 'Радиомодуль не найден');
        }

        $rmoduleData = Yii::$app->request->post('Rmodule', False);

        if ($rmoduleData) {
            $this->model->setAttributes($rmoduleData, false);


            $events = new Events();
            $events->oldAttributes = $this->model->getOldAttributes();
            $events->region_id = $this->model->geo_location_id;

            if ($this->model->save()) {

                $events->newAttributes = $this->model->getAttributes();
                $events->model = $this->model;
                $events->type = 'edit';
                $events->AddEvent();

                return Yii::$app->controller->redirect('index');
            }
            else
            {
                return Yii::$app->controller->refresh();
            }
        }



    }



}