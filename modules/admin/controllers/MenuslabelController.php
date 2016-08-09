<?php

namespace app\modules\admin\controllers;
use app\models\MenuItem;
use app\models\MenuItemSearch;
use Yii;
use app\models\MenusLabel;
use app\models\MenusLabelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenusLabelController implements the CRUD actions for MenusLabel model.
 */
class MenuslabelController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all MenusLabel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MenuItemSearch();

        $searchModel->menu_id=Yii::$app->request->get('menuId',null);
        $searchModel->level=Yii::$app->request->get('level',null);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MenusLabel model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }



    public function actionUpdate($id)
    {

        $langArr=Yii::$app->request->post('Lang',[]);
        $idp=Yii::$app->request->post('id',false);

        if($idp) {
            foreach ($langArr as $key => $value) {

                $menusLabel = MenusLabel::find()
                    ->where(['menu_item_id' => $idp])
                    ->andWhere(['lang_id' => $key])
                    ->one();

                if (!isset($menusLabel)) {
                    $menusLabel = new MenusLabel();
                }
                $menusLabel->menu_item_id=$idp;
                $menusLabel->label = $value;
                $menusLabel->lang_id = $key;
                $menusLabel->save();

            }
        }

        if(isset($menusLabel)and empty($menusLabel->getErrors())){
            return $this->redirect(['index']);
        }

        return $this->render('edit', [
            'model' => MenuItem::findOne($id),
        ]);
    }
    /**
     * Creates a new MenusLabel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MenusLabel();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MenusLabel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MenusLabel model.
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
     * Finds the MenusLabel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MenusLabel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MenusLabel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
