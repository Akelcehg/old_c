<?php

namespace app\modules\counter\controllers;

use app\components\ChartCalc;
use app\models\Modem;
use app\models\ModemDCommandConveyor;
use app\models\SimCard;
use app\modules\admin\components\Counter;
use app\modules\counter\components\ForcedPaymentButton;
use app\modules\counter\components\GetPacketButton;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class AjaxController extends Controller
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

                                'ajaxcheckforcedpayment',
                                'ajaxgetpacket',

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

     public function actionAjaxcheckforcedpayment($modem_id)
    {



        $this->layout='onlyGrid';
        $modem=Modem::find()->where(['serial_number'=>$modem_id])->one();

        $modemDconv=new ModemDCommandConveyor();
        $modemDconv->modem_id=$modem_id;
        $modemDconv->command=$modem->simCard->request_forced_payment;
        $modemDconv->status="ACTIVE";
        $modemDconv->save();

        return ForcedPaymentButton::widget(['modem'=>$modem]);
    }

    public function actionAjaxgetpacket($modem_id)
    {



        $this->layout='onlyGrid';
        $modem=Modem::find()->where(['serial_number'=>$modem_id])->one();

        $modemDconv=new ModemDCommandConveyor();
        $modemDconv->modem_id=$modem_id;
        $modemDconv->command=$modem->simCard->request_get_packet;
        $modemDconv->status="ACTIVE";
        $modemDconv->save();

        return GetPacketButton::widget(['modem'=>$modem]);
    }



}
