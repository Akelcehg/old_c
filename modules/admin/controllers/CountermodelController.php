<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 04.01.16
 * Time: 11:40
 */

namespace app\modules\admin\controllers;


use yii\web\Controller;
use Yii;
use app\models\CounterModel;
use app\components\Events;
use app\models\User;

class CountermodelController  extends Controller
{

   // public $layout = 'smartAdmin';


    public function actionViewcountermodel()
    {

        $searchModel = new \app\models\CounterModelSearch;
        $searchModel->type = Yii::$app->request->get('type', null);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        echo $this->render('counterModel', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    public function actionEditcountermodel()
    {

        $id = Yii::$app->request->get('id', FALSE);
        $counterModelsData = Yii::$app->request->post('CounterModel', False);

        $counterModels = CounterModel::find()->where(['id' => $id])->one();

        if(!$counterModels)
        {
            throw new HttpException(404, 'Модель счетчика не найдена');
        }

        if ($counterModelsData) {

            $counterModels->setAttributes($counterModelsData, false);

            $events = new Events();
            $currentUser = new  User();
            $events->oldAttributes = $counterModels->getOldAttributes();
            $events->counter_type = $counterModels->type;
            $events->region_id = $currentUser->getCurrentUserRegionId(Yii::$app->user->id);

            if ($counterModels->save()) {

                $events->newAttributes = $counterModels->getAttributes();
                $events->model = $counterModels;
                $events->type = 'edit';
                // $events->description = 'Редактирование Модели Cчетчика №' . $id;
                $events->AddEvent();

                //Events::AddEvent('edit','Редактирование Модема №'.$id,$modem);
                //$modem->changeCountersAddress();
                return $this->redirect('viewcountermodel');
            }
        }


        echo $this->render('editCounterModel', [
            'counterModels' => $counterModels,
        ]);
    }

    public function actionAddcountermodel()
    {


        $counterModelsData = Yii::$app->request->post('CounterModel', False);

        $counterModels = new CounterModel();

        if ($counterModelsData) {

            $counterModels->setAttributes($counterModelsData, false);


            if ($counterModels->save()) {

                $events = new Events();
                $currentUser = new  User();
                $events->counter_type = $counterModels->type;
                $events->region_id = $currentUser->getCurrentUserRegionId(Yii::$app->user->id);
                $events->newAttributes = $counterModels->getAttributes();
                $events->model = $counterModels;
                $events->type = 'add';
                $events->AddEvent();

                return $this->redirect('viewcountermodel');
            }
        }


        echo $this->render('addCounterModel', [
            'counterModels' => $counterModels,
        ]);
    }

    public function actionDeletecountermodel()
    {

        $id = Yii::$app->request->get('id', FALSE);

        CounterModel::deleteAll(['id' => $id]);

        return $this->redirect('viewcountermodel');
    }

}