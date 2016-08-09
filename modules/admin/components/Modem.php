<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 11.01.16
 * Time: 9:01
 */

namespace app\modules\admin\components;
use app\models\SimCard;
use yii\base\Component;
use Yii;
use app\models\User;
use app\components\CounterQuery;
Use yii\data\ActiveDataProvider;
use app\models\ModemSearch;
use app\modules\admin\components\AdminComponent;
use app\components\RightsComponent;
use app\models\Counter;
use app\components\Events;
use yii\web\HttpException;




class Modem extends AdminComponent
{



    public function ModemList(){

        $this->model=\app\models\Modem::find()->joinWith('address');

        $this->searchModel=new ModemSearch();
        $this->searchModel->distinct=true;

        $this->searchModel->phone_number=Yii::$app->request->get('phone', null);
        $this->searchModel->serial_number=Yii::$app->request->get('serial', null);
        $this->searchModel->alertType=Yii::$app->request->get('alerts_type', null);
        $this->searchModel->geo_location_id=Yii::$app->request->get('geo_location_id', null);

        $rights = new  RightsComponent();
        $this->searchModel = $rights->updateSearchModelCounter($this->searchModel);

        $this->dataProvider=$this->searchModel->search(Yii::$app->request->queryParams);



        return true;

    }

    public function EditModem($redirect=true)
    {

        if (!($serial_number = Yii::$app->request->get('serial_number', FALSE))) {
            $arr = Yii::$app->request->post('Modem', FALSE);
            $serial_number = $arr['serial_number'];
        }

        $this->model =\app\models\Modem::find()->where(['serial_number' => $serial_number])->one();

        if(!$this->model)
        {
            throw new HttpException(404, 'Модем не найден');
        }


        $modemData = Yii::$app->request->post('Modem', False);
        $this->model->setScenario('editModem');
        if ($modemData) {
            $this->model->setAttributes($modemData, false);

            $currentUser = new  User();
            $events = new Events();
            $events->oldAttributes = $this->model->getOldAttributes();
            $events->region_id = $this->model->geo_location_id;

            if ($this->model->save()) {

                $events->newAttributes = $this->model->getAttributes();
                $events->model = $this->model;
                $events->type = 'edit';
                // $events->description = 'Редактирование Модема №' . $serial_number;
                $events->AddEvent();

                //Events::AddEvent('edit','Редактирование Модема №'.$id,$modem);
                // $modem->changeCountersAddress();
                if($redirect) {
                    return Yii::$app->controller->redirect('modemslist');
                }
                else
                {
                    Yii::$app->controller->refresh();
                }
            }
        }

        $countersQuery = Counter::find()->where(['modem_id' => $this->model->serial_number]);


        if (User::is('admin')) {
            // $countersQuery->andFilterWhere(['type' => $this->type]);
        }

        if (User::is('admin_gas')) {
            $countersQuery->andWhere(['type' => 'gas']);
        }

        if (User::is('admin_water')) {
            $countersQuery->andWhere(['type' => 'water']);
        }


        $this->dataProvider = new ActiveDataProvider([
            'query' => $countersQuery,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);
    }


    public function EditSimCard()
    {
        $modem_id=Yii::$app->request->get('modem_id', FALSE);
        $this->model = SimCard::find()->where(['modem_id'=>$modem_id])->one();

        if(!$this->model)
        {
            throw new HttpException(404, 'Модем не найден');
        }

        $simCardPost=Yii::$app->request->post('SimCard', FALSE);
        if($simCardPost){

            $this->model->setAttributes($simCardPost,false);
            $this->model->save();

        }

    }


}