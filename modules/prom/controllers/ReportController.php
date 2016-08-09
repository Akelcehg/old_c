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
use app\components\Prom\Prom;
use app\models\CorrectorToCounter;
use app\models\Intervention;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class ReportController extends Controller
{
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
                                'getreport',
                                'generateDayReports',
                                'getmomentdata'
                            ],
                            'allow' => true,
                            //Admin and prom admin can  make actions to addresses
                            'roles' => ['PromAdmin'],
                        ]
                        ,
                    ]
                ,
            ],
        ];
    }




    public function actionGetmomentdata() {

        $id=Yii::$app->request->get("id");

        $corrToCount=CorrectorToCounter::findOne($id);
        $flouTechComGen = new FlouTechCommandGenerator();
        $flouTechComGen->counter_id=$id;
        $flouTechComGen->MomentDataCommand([1=>$corrToCount->branch_id]);
        return true;

    }


    public function actionGetunikinterv() {

       $interv=Intervention::find()->all();


       for($i=0;$i<count($interv);$i++){
        foreach($interv as $int){

            if(($interv[$i]->month==$interv->month)and($interv[$i]->year==$interv->year)and($interv[$i]->day==$interv->day)and($interv[$i]->hour==$interv->hour)and($interv[$i]->minutes==$interv->minutes)and($interv[$i]->seconds==$interv->seconds)and($interv[$i]->params==$interv->params)){
                $int->delete();
            }

        }
        }

    }


      public function actionGetreport()
    {


        $counterId=Yii::$app->request->get('counterId');

        if(9>date("H")){
            $date=Yii::$app->request->get('date',date("Y-m-d",time()-3600*24*2));
        }else{
            $date=Yii::$app->request->get('date',date("Y-m-d",time()-3600*24));
        }

        $type=Yii::$app->request->get('type','day');
        $format=Yii::$app->request->get('format','pdf');

        $prom=new Prom();
        $prom->id=$counterId;
        $prom->date=$date;
        $prom->type=$type;
        $prom->format=$format;
        return $prom->GetReport();
    }



}