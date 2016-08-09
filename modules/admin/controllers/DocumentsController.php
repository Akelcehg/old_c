<?php

namespace app\modules\admin\controllers;

use app\components\RightsComponent;
use app\models\User;
use DateTime;
use Yii;
use app\models\Documents;
use app\models\SearchDocuments;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\components\Events;

/**
 * DocumentsController implements the CRUD actions for Documents model.
 */
class DocumentsController extends Controller
{
  //  public $layout = 'smartAdmin';

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
                                'update',
                                'create',
                                'delete',
                                'deleteimage'

                            ],
                            'allow' => true,
                            'roles' => ['admin', 'regionWatcher', 'waterWatcher', 'gasWatcher'],
                        ]
                        ,
                    ]
                ,
            ],
        ];
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

    /**
     * Lists all Documents models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchDocuments();

        $rights = new  RightsComponent();
        $searchModel = $rights->updateSearchModel($searchModel);

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Documents model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Documents model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Documents();

        //print_r(Yii::$app->request->post());
        if ($model->load(Yii::$app->request->post())) {

            $model->user_id = Yii::$app->user->id;
            $date = new DateTime();
            $model->created_at = $date->getTimestamp();




            if ($model->save()) {

                $events = new Events();
                $events->newAttributes = $model->getAttributes();
                $events->model = $model;
                $events->type = 'add';
                $events->AddEvent();

                if (isset(Yii::$app->request->post()['DocumentImages'])) {
                    $documentImages = Yii::$app->request->post()['DocumentImages'];

                    $path = 'img/documents/' . $model->id;

                    if (!file_exists($path)) {
                        mkdir($path);
                    }

                    foreach ($documentImages as $image) {

                        file_put_contents($path . '/' . time() . '.png', fopen($image, 'r'));

                    }
                }
                return $this->redirect(['update', 'id' => $model->id]);
            } else  return $this->render('create', [
                'model' => $model,
            ]);

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Documents model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $path = 'img/documents/' . $model->id;


        $events = new Events();
        $events->oldAttributes=$model->getOldAttributes();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {


            $events->newAttributes = $model->getAttributes();
            $events->model = $model;
            $events->type = 'edit';
            $events->AddEvent();

            if (isset(Yii::$app->request->post()['DocumentImages'])) {
                $documentImages = Yii::$app->request->post()['DocumentImages'];

                if (!file_exists('img/documents')) {
                    mkdir('img/documents');
                }
                if (!file_exists($path)) {
                    mkdir($path);
                }

                foreach ($documentImages as $image) {
                    file_put_contents($path . '/' . time() . '.png', fopen($image, 'r'));
                }
            }
            return $this->redirect(['update', 'id' => $model->id]);

        } else {
            {
                return $this->render('update', [
                    'model' => $model,
                    'imagesArray' => glob($path . '/*.*')
                ]);
            }
        }
    }

    /**
     * Deletes an existing Documents model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Documents model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Documents the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Documents::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
