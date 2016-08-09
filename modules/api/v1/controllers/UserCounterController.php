<?php
/**
 * Date: 7/15/15
 * Time: 6:55 AM
 */
namespace app\modules\api\v1\controllers;


use app\components\Counter;
use yii\rest\Controller;
use app\models\UserCounters;
use yii\helpers\Json;
use Yii;

class UserCounterController extends Controller
{
    public $modelClass = 'app\modules\api\v1\models\UserCounter';

    public function actions()
    {
        $actions = parent::actions();

        $actions['index'] = [
            'class' => 'app\modules\api\v1\actions\SearchAction',
            'modelClass' => $this->modelClass,
        ];

        $actions['view'] = [
            'class' => 'yii\rest\ViewAction',
            'modelClass' => $this->modelClass,
        ];

        $actions['create'] = [
            'class' => 'yii\rest\CreateAction',
            'modelClass' => $this->modelClass,
        ];

        $actions['update'] = [
            'class' => 'yii\rest\UpdateAction',
            'modelClass' => $this->modelClass,
            'scenario' => 'editCounter'
        ];

        return $actions;
    }

    public function actionEditCounter()
    {

        $request = Yii::$app->request;

        $counter = UserCounters::find()->where(['id' => $request->getBodyParam('id')])->one();

        $counterData = Yii::$app->request->getBodyParams();

        $counter->setScenario('editCounter');

        $hasImage = false;

        $image = $request->getBodyParam('image');
        if ($image) {

            $hasImage = true;

        }

        $counterComponent = new Counter($counter, $counterData, []);

        $response = [["status" => "false"]];

        if ($counterComponent->saveWithImage($hasImage, $image)) $response[0]["status"] = true;

        return $response;

    }

    public function actionCounterImage()
    {
        $id = Yii::$app->request->get('id', False);

        $response = [array("image" => "false")];

        $im1 = Yii::$app->basePath . '/www/gas/' . $id . '.png';

        if (file_exists($im1)) {
            $response[0]["image"] = '/gas/' . $id . '.png';
        }

        return $response;
    }

}