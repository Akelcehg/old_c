<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Counter;
use app\models\Indication;
use app\models\Modem;
use PHPExcel_IOFactory;
use yii\console\Controller;
use Yii;
use app\components\TelegramAlertNotification;
use app\components\Alerts;
use app\models\AlertsList;
use yii\db\Expression;


/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */

class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
{
    echo $message . "\n";
}

    public function actionPublicKey($path)
    {
        // /etc/apache2/ssl/aser.com.ua.key;

       $file=fopen($path,'r');
        $array=openssl_pkey_get_details($file);
        print_r($array);

    }

    public function actionTelegramSendTest($id)
    {
        // /etc/apache2/ssl/aser.com.ua.key;
        if(Yii::$app->params['TelegramAlertNotificationEnabled']) {
            $telegram = new TelegramAlertNotification();
            $telegram->telegramList = Alerts::TelegramArrayGenerate('leak');
            $telegram->alertId = $id;
            $telegram->send();
        }

    }

    public function actionLastIndicationFromIndications()
    {
        $counters=Counter::find()->all();
        foreach($counters as $counter){
            $indication = Indication::find()->where(['counter_id'=>$counter->id])->orderBy(['created_at'=>SORT_DESC])->one();
            if($indication) {
                $counter->last_indications = $indication->indications;
            }
            else
            {
                $counter->last_indications = $counter->initial_indications;
            }
            $counter->save();
        }


    }

    public function actionAddInAlertsList($serialNumber, $type, $device_type='counter') {

        $alertListCheck = AlertsList::find()->where(['serial_number' => $serialNumber, 'type' => $type, 'device_type'=>$device_type, 'status' => 'ACTIVE'])->all();

        if (!isset($alertListCheck[0])) {

            $alertsList = AlertsList::findOne(['serial_number'=>$serialNumber,'type'=>$type]);

            if ($alertsList) {



                if(Yii::$app->params['TelegramAlertNotificationEnabled']){
                    $telegram = new TelegramAlertNotification();
                    $telegram->telegramList = Alerts::TelegramArrayGenerate($type);
                    $telegram ->alertId = $alertsList->id;
                    $telegram->send();

                }

               // $alert = new AlertsList();
                //$alert->type =$type;


                //$events = new Events();
                //$events->type = 'alert';
                //$events->model= $alertsList;
                //$events->description = 'Приём Предупреждения-'.$alert->getAlertTypeText().' № счетчика'.$serialNumber;
               // $events->AddEvent();


                //Events::AddEvent('alert','Принятие Предупреждения-'.$type.' № счетчика'.$serialNumber,$alertsList);

                return true;
            } else {
                return false;
            }
        }
    }


    public function actionImgToFolder() {

        $path="www/img/gas";

        $files1 = scandir($path);

        foreach($files1 as $file) {

            $file1=explode('.', $file);
            if(!is_dir("www/img/gas/" .$file)) {
                if(!file_exists("www/img/gas/" . $file1[0])) {
                    mkdir("www/img/gas/" . $file1[0]);
                }
                copy("www/img/gas/" . $file, "www/img/gas/" . $file1[0] . "/" . $file);
            }

            }

    }

    public function actionCheckModem() {


        $modem=Modem::find()->where(['serial_number'=>30])->andWhere("updated_at< (NOW()+INTERVAL 1 MINUTE) AND updated_at>(NOW()-INTERVAL 1 MINUTE)")->one();

        if($modem){
            $body="30 МОДЕМ ВЫШЕЛ НА СВЯЗЬ!!!!!!!!!!!";
             Yii::$app->bot->sendMessage(104120629, $body);
             Yii::$app->bot->sendMessage(58966980, $body);
             Yii::$app->bot->sendMessage(45162409, $body);

        }


    }

    public function actionCheck(){
        $body="30 МОДЕМ ВЫШЕЛ НА СВЯЗЬ!!!!!!!!!!!";

        Yii::$app->bot->sendMessage(104120629, $body);
        Yii::$app->bot->sendMessage(58966980, $body);
        Yii::$app->bot->sendMessage(45162409, $body);
    }


public function actionPromexport(){

    $inputFileName="www/jrnl.xls";

    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);

    $sheet = $objPHPExcel->getSheet(0);
    $highestRow = $sheet->getHighestRow();
    $highestColumn = 6;
    $rowData=[];
    $header = $sheet->rangeToArray('A' ."2". ':' . $highestColumn . "2",
        NULL,
        TRUE,
        FALSE);
//  Loop through each row of the worksheet in turn
   for ($row = 3; $row <= $highestRow; $row++){
        //  Read a row of data into an array
        $rowData[] = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
            NULL,
            TRUE,
            FALSE);
        //  Insert row data array into your database of choice here
    }

    print_r(array_combine($header,$rowData));

}


}
