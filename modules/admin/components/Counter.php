<?php

/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 04.01.16
 * Time: 9:58
 */
namespace app\modules\admin\components;

use yii\base\Component;
use app\models\Address;
use app\models\AddressSearch;
use Yii;
use app\components\RightsComponent;
use app\models\CounterSearch;
use yii\data\ActiveDataProvider;
use app\modules\admin\components\AdminComponent;
use yii\web\HttpException;


class Counter extends AdminComponent
{


    public function CounterAddressList(){
        $user_type = Yii::$app->request->get('user_type', false);
        if(!$user_type) {
            $user_type = Yii::$app->request->post('user_type', 0);
        }

        $this->model = new Address();
        $searchModel = new AddressSearch();

        $searchModel->id = Yii::$app->request->post('id', 0);
        $searchModel->beginDate = Yii::$app->request->post('beginDate', Yii::$app->params['beginDate']);
        $searchModel->region_id = Yii::$app->request->post('region_id',null);
        $searchModel->endDate = Yii::$app->request->post('endDate', date('Y-M-d'));
        $searchModel->status = Yii::$app->request->post('status','ACTIVE');
        $searchModel->isCounter = Yii::$app->request->post('isCounter',true);
        $searchModel->user_type = $user_type;

        if(Yii::$app->request->post('id',false)){
            $searchModel->id = Yii::$app->request->post('id', 0);
        }else{
            $searchModel->id = Yii::$app->request->get('geo_location_id', 0);
        }

        $rights = new  RightsComponent();
        $searchModel = $rights->updateSearchModelCounter($searchModel);

        $this->dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->searchModel = $searchModel;

        return true;
    }


    public function CounterList(){

        $this->model = \app\models\Counter::find()
            ->joinWith('user')
            ->joinWith('address')
            ->orderBy('flat');

        $searchModel = new CounterSearch();

        $searchModel->updated_at = Yii::$app->request->get('updated_at');
        $searchModel->user_type=Yii::$app->request->get('user_type',null);
        $searchModel->region_id = Yii::$app->request->get('region_id',null);
        $searchModel->exploitation = Yii::$app->request->get('exploitation', null);
        $searchModel->flat = Yii::$app->request->get('flat');
        $searchModel->modem_id = Yii::$app->request->get('modem_id');
        $searchModel->serial_number = Yii::$app->request->get('serial_number');
        $searchModel->updated_at = Yii::$app->request->get('updated_at');
        $searchModel->beginDate = Yii::$app->request->get('beginDate', Yii::$app->params['beginDate']);
        $searchModel->endDate = Yii::$app->request->get('endDate', date('Y-M-d'));
        $searchModel->fulladdress = Yii::$app->request->get('fulladdress', null);
        $searchModel->geo_location_id = Yii::$app->request->get('geo_location_id',null);
        $searchModel->rmodule_id = Yii::$app->request->get('rmodule_id',null);
        $searchModel->pagination = ['pageSize' => $this->paginationSize];

        $rights = new  RightsComponent();
        $searchModel = $rights->updateSearchModelCounter($searchModel);

        $this->dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $this->searchModel=$searchModel;

        return true;
    }

    public function EditCounter(){

        if (!($id = Yii::$app->request->get('id', FALSE))) {
            $arr = Yii::$app->request->post('Counters', FALSE);
            $id = $arr['id'];
        }

        $counter = \app\models\Counter::find()->where(['id' => $id])->one();
        $this->model=$counter;

        if(!$counter)
        {

            throw new HttpException(404, 'Счетчик не найден');

        }

        $counterData = Yii::$app->request->post('Counter', False);

        $counter->setScenario('editCounter');

        if ($counterData) {

            $hasImage = false;

            $image = Yii::$app->request->post('Counter', False)["image"];

            if ($image) {

                $hasImage = true;
            }

            $counterComponent = new \app\components\Counter($counter, $counterData, []);
            if ($counterComponent->saveWithImage($hasImage, $image)) {

                return true;

            }
        } else {
            return false;

        }

    }


}