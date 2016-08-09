<?php
namespace app\modules\counter\controllers;


use app\models\Search;
use app\modules\admin\components\Counter;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class SearchController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' =>
                    [
                        [
                            'actions' => [
                                'index',
                                'autocomplete'
                            ],
                            'allow' => true,
                            'roles' => ['admin', 'gasWatcher', 'waterWatcher', 'regionWatcher'],
                        ]
                        ,
                    ]
                ,
            ],
        ];
    }
    public function actionIndex()
    {


        $this->layout='smartAdminBigImage';




        return $this->render('index');


    }


    public function actionAutocomplete()
    {
        Yii::$app->response->format = 'json';
        $text=Yii::$app->request->get("term",false);
        $search=Search::find()->where(['like','search_string',$text])->asArray()->all();


        return $search;

    }
}