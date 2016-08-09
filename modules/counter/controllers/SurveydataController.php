<?php

namespace app\modules\counter\controllers;

use app\components\ChartCalc;
use app\models\Modem;
use app\models\ModemDCommandConveyor;
use app\models\SimCard;
use app\models\SurveyData;
use app\modules\admin\components\Counter;
use app\modules\counter\components\ForcedPaymentButton;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\HttpException;

class SurveydataController extends Controller
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = 'smartAdminN';

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     *
     * @see http://www.yiiframework.com/wiki/65/how-to-setup-rbac-with-a-php-file/
     * http://www.yiiframework.com/wiki/253/cphpauthmanager-how-it-works-and-when-to-use-it/
     *
     * @return array access control rules
     */
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
                                'create',
                                'update',
                                'deleteimage'
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

        $surveyData=SurveyData::find();

        $surveyData2=new SurveyData();


        $dataProvider = new ActiveDataProvider([
            'query' => $surveyData,
        ]);


        return $this->render('index', [
            'dataProvider' =>  $dataProvider,
            'searchModel'=> $surveyData2
        ]);

    }

    public function actionCreate()
    {

        $surveyData=new SurveyData();


        $data=Yii::$app->request->post('SurveyData',[]);
        if(!empty($data)){
            $surveyData->setAttributes($data,false);
            if($surveyData->save()){
                Yii::$app->session->addFlash('SurveyCreated','Акт создан успешно',false);
                $this->redirect(['update','id'=>$surveyData->id]);
            };
        }
        $this->SaveImages($surveyData->id);

        return $this->render('create', [
            'model'=> $surveyData
        ]);

    }

    public function actionUpdate()
    {
        $id=Yii::$app->request->get('id',null);
        $surveyData=SurveyData::find()->where(['id'=>$id])->one();

        if(!$surveyData){
            throw new HttpException(404, 'Акт Обследования не найден');
        }

         $data=Yii::$app->request->post('SurveyData',[]);
        if(!empty($data)){
            $surveyData->setAttributes($data,false);
            if($surveyData->save()){
                Yii::$app->session->addFlash('SurveyEdited','Акт отредактирован успешно',false);
                $this->refresh();
            };
        }
        $this->SaveImages($surveyData->id);

        return $this->render('update', [
            'model'=> $surveyData,
            'imagesArray' =>$this->GetImages($surveyData->id)
        ]);

    }


    public function actionDeleteimage()
    {
        $file = Yii::$app->request->post('path');

        if (file_exists($file)) {
            unlink($file);
            return 'true';
        } else {
            return 'false';
        }

    }

    public function SaveImages($id){
        if (isset(Yii::$app->request->post()['Images'])) {
            $documentImages = Yii::$app->request->post()['Images'];

            $path = 'img/survey/' . $id;

            if (!file_exists($path)) {
                mkdir($path);
            }

            foreach ($documentImages as $image) {

                file_put_contents($path . '/' . time() . '.png', fopen($image, 'r'));

            }
        }
    }


    public function GetImages($id){
        $path = 'img/survey/' . $id;
        return glob($path . '/*.*');
    }


}
