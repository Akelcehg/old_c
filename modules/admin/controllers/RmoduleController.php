<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 11.01.16
 * Time: 11:10
 */

namespace app\modules\admin\controllers;


use app\modules\admin\components\Rmodule;
use yii\web\Controller;

class RmoduleController extends Controller
{
   // public $layout = 'smartAdmin';

    public function actionIndex(){

        $rmodule = new Rmodule();
        $rmodule->RmoduleList();

        return $this->render('index', [
            'dataProvider' =>  $rmodule->getDataProvider(),
            'rmodule' => $rmodule->getModel(),
            'searchModel' => $rmodule->getSearchModel(),
        ]);

    }

    public function actionEditrmodule(){

        $rmodule=new \app\modules\admin\components\Rmodule();
        $rmodule->EditRmodule();


        echo $this->render('editrmodule', [
                'rmodule' => $rmodule->getModel()

        ]);

    }

}