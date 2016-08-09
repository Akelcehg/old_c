<?php
namespace app\components\Events;
use app\components\Events\handlers\AddressHandler;
use app\components\Events\handlers\AlertHandler;
use app\components\Events\handlers\CorrectionHandler;
use app\components\Events\handlers\CounterHandler;
use app\components\Events\handlers\DefaultHandler;
use app\components\Events\handlers\ModemHandler;
use app\components\Events\handlers\OptionHandler;
use app\components\Events\handlers\RmoduleHandler;
use app\components\Events\handlers\UserHandler;
use app\components\Events\handlers\DocumentHandler;

use app\components\Events\handlers\CounterModelHandler;

/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 13.01.16
 * Time: 15:51
 */
class EventFactory
{

    public function createEvent($classname)
    {
        $output=null;

        switch($classname){
            case \app\models\Counter::className():
                $output=new CounterHandler();
                break;

            case \app\models\AlertsList::className():
                $output=new AlertHandler();
                break;

            case \app\models\Modem::className():
                $output=new ModemHandler();
                break;

            case \app\models\Rmodule::className():
                $output=new RmoduleHandler();
                break;

            case \app\models\User::className():
                $output=new UserHandler();
                break;

            case \app\models\Address::className():
                $output=new AddressHandler();
                break;

            case \app\models\Documents::className():
                $output=new DocumentHandler();
                break;

            case \app\models\Correction::className():
                $output=new CorrectionHandler();
                break;

            case \app\models\CounterModel::className():
                $output=new CounterModelHandler();
                break;

            case \app\models\Option::className():
                $output=new OptionHandler();
                break;

            default:
                $output=new DefaultHandler();
        }

        return $output;

    }


}