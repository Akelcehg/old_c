<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 16.02.16
 * Time: 16:16
 */

namespace app\modules\prom\controllers;


use app\components\FlouTechCommandGenerator;
use app\components\FlouTechReportGenerator;
use app\models\CommandAskAnswerSearch;
use app\models\CommandConveyorSearch;
use app\models\CorrectorToCounter;
use app\models\Intervention;
use app\models\ModemStatus;
use app\modules\prom\components\ConnectWidget;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

class DebugController extends Controller
{
    public $layout = 'smartAdmin';
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
                                'index2',
                                'modem',
                                'onemodem',
                                'conveyor',
                                'askanswer',
                                'log'

                            ],
                            'allow' => true,
                            //Admin and prom admin can  make actions to addresses
                            'roles' => ['superadmin'],
                        ]
                        ,
                    ]
                ,
            ],
        ];
    }


    public function actionIndex() {


        $data = CorrectorToCounter::find();

        $dataProvider = new ActiveDataProvider([
            'query' =>$data,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        return $this->render('index',[
            'dataProvider' => $dataProvider,
        ]);


    }


    public function actionModem() {


        $data = ModemStatus::find();

        $dataProvider = new ActiveDataProvider([
            'query' =>$data,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        $this->layout='smartAdminN';

        return $this->render('modems',[
            'dataProvider' => $dataProvider,
        ]);


    }


    public function actionOnemodem() {

        $id=Yii::$app->request->get('id');


        return ConnectWidget::widget(['id'=>$id]);


    }

    public function actionIndex2() {


        $data = CorrectorToCounter::find();

        $dataProvider = new ActiveDataProvider([
            'query' =>$data,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);
        $this->layout='smartAdminN';

        return $this->render('index2',[
            'dataProvider' => $dataProvider,
        ]);


    }

    public function actionLog() {

        if(file_exists('../serverlog/connectLog.txt')){
            $string=file_get_contents('../serverlog/connectLog.txt');
        }else{
            $string='no file';
        }

        $stringArr=explode(";",$string);
        $stringElemArr=[];
        foreach($stringArr as $oneStringArr){
            $buf=explode(" ",$oneStringArr);
            if(isset($buf[1])){
                $stringElemArr[]=[
                'date'=>$buf[1],
                'time'=>$buf[2],
                'modem№'=>$buf[6],
            ];
            }
        }

        $dataProvider= new ArrayDataProvider([
            'allModels' =>  $stringElemArr,
            'pagination' => [
                'pageSize' => 50,
            ],
            'sort' => [
                'attributes' => ['modem№'],
            ],
        ]);

        return $this->render('log',[
            'dataProvider' => $dataProvider,
        ]);


    }

    public function actionConveyor() {

        $searchModel = new CommandConveyorSearch();
        $searchModel->modem_id = Yii::$app->request->get('modem_id');
        $searchModel->command = Yii::$app->request->get('command');
        $searchModel->command_type = Yii::$app->request->get('command_type');
        $searchModel->status = Yii::$app->request->get('status');
        $searchModel->created_at = Yii::$app->request->get('created_at');
        $searchModel->pending_at = Yii::$app->request->get('pending_at');
        $searchModel->disabled_at = Yii::$app->request->get('disabled_at');
        $searchModel->pagination = ['pageSize' => 15];//$this->paginationSize

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('conveyor',[
            'dataProvider' => $dataProvider,
            'searchModel'=>$searchModel,

        ]);


    }

    public function actionAskanswer() {

        $searchModel = new CommandAskAnswerSearch();
        $searchModel->modem_id = Yii::$app->request->get('modem_id');
        $searchModel->corrector_id = Yii::$app->request->get('corrector_id');
        $searchModel->branch_id = Yii::$app->request->get('branch_id');
        $searchModel->ask = Yii::$app->request->get('ask');
        $searchModel->answer = Yii::$app->request->get('answer');
        $searchModel->command = Yii::$app->request->get('command');
        $searchModel->answered_at = Yii::$app->request->get('answered_at');
        $searchModel->pagination = ['pageSize' => 15];//$this->paginationSize

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('askAnswer',[
            'dataProvider' => $dataProvider,
            'searchModel'=>$searchModel,

        ]);


    }







}