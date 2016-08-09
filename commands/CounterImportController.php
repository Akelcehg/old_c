<?php



namespace app\commands;

use app\models\Address;
use app\models\Counter;
use app\models\Indication;
use app\models\Modem;
use app\models\Region;
use app\models\Rmodule;
use yii\console\Controller;
use app\models\UserCounters;
use app\models\UserModems;
use app\models\UserIndications;
use app\models\CounterAddress;
use app\models\Regions;
use app\models\CounterModel;

/**
 * Counter Import Command From TSV File. Change separator if you want use it for different formats like csv or different
 *
 * Class CounterImportController
 * @package app\commands
 */
class CounterImportController extends Controller {

    public function actionIndex($path) {

        ini_set('auto_detect_line_endings', true);
        $separator = "\t";

        $requiredFields = [
            'fio'
        ];

        $fieldsMapper = [
            'fullname' => 'fio',
            'counter_model' => 'counter_model_name',
        ];
        
        if (($handle = fopen($path, "r")) !== false) {
            
            //find first ling and set it as header
            while (($data = fgetcsv($handle, 0, $separator)) !== false) {
                if (count(array_filter($data)) > 0) {
                    $headers = $data;
                    array_walk($headers, function (&$header) {
                        $header = strtolower($header);
                    });
                    break;
                }
            }

            $lineIndex=2;
            $addedCount=0;
            $updatedCount=0;
            
            //check required fields
            $existRequiredFields = array_intersect($headers, $requiredFields);
            if (count($existRequiredFields) != count($requiredFields)) {
                echo "Some required fields not exist in csv file : " . implode(", ", array_diff($requiredFields, $existRequiredFields)) . "\n";
                exit();
            }


            //start importing
            while (($data = fgetcsv($handle, 0, $separator)) !== false) {
                $row = array_combine($headers, $data);
                
                $isUpdate=false;

               // $counterAddress1 = CounterAddress::findOne(['id' => '9']);




                $fullname = trim($row[$fieldsMapper['fullname']]);
                $counter_model = trim($row[$fieldsMapper['counter_model']]);

                //@TODO - Implement should be there
                $region = Region::find()->where(['name' => $row['city']])->one();
                $counterAddress = Address::find()->where(['region_id' => $region->id])
                        ->andWhere(['street' => $row['street']])
                        ->andWhere(['house' => trim($row['house'])])
                        ->one();


                $userCounter = Counter::findOne(['serial_number' => $row['real_serial_number']]);

                $rmodule= Rmodule::findOne(['serial_number' => $row['serial_number']]);

                if (!$userCounter) {

                    $userCounter = new Counter();



                    $text = " counter " . $row['serial_number'] . " added (".$lineIndex." line) \n";
                } else {
                    $isUpdate=true;
                    $text = " counter " . $row['serial_number'] . " updated (".$lineIndex." line) \n";
                }

                if(!$rmodule){
                    $rmodule = new Rmodule();
                    $rmodule->serial_number=$row['serial_number'];
                    $rmodule->update_interval = 24;
                    $rmodule->modem_id=$row['user_modem_id'];
                    $rmodule->save();
                }

                $modem = Modem::findOne(['serial_number' => $row['user_modem_id']]);

                if (!$modem) {

                    $modem = new Modem();
                    $modem->serial_number = $row['user_modem_id'];
                    $modem->geo_location_id = $counterAddress->id;

                    if ($modem->save()) {
                        echo "modem" . $modem->id . " added \n";
                    }
                }



                $userCounter->modem_id = $row['user_modem_id'];
                $userCounter->rmodule_id = $row['serial_number'];
                $userCounter->serial_number = $row['real_serial_number'];
                $userCounter->initial_indications = $row['initial_indications'];
                $userCounter->last_indications = $row['initial_indications'];



                $userCounter->flat = $row['flat'];
                $userCounter->fullname = $fullname;



                $counterModel = CounterModel::findOne(['name' => $counter_model]);
                if(isset($counterModel)) {
                    $userCounter->counter_model = $counterModel->id;
                }
                else
                {
                    echo ' Counter model '. $counter_model.' not found'."\n";
                    break;

                }




                if(isset($counterAddress)) {
                    $userCounter->geo_location_id = $counterAddress->id;
                }
                else
                {
                    echo ' Address '.$row['city'].' '. $row['street'].' '.trim($row['house']).' not found'."\n";
                    break;
                }


                if ($userCounter->save()) {
                    $rmodule->counter_id=$userCounter->id;
                    $rmodule->save();

                    echo $text;
                   
                    if($isUpdate)
                        {
                            $updatedCount++;
                        }
                        else
                        {
                            $addedCount++;
                        }
                     

                    $indications = Indication::find()->where(['counter_id' => $userCounter->serial_number])->orderBy('created_at')->one();
                    if (!$indications) {
                        $userIndication = new Indication();
                        $userIndication->indications = $row['initial_indications'];
                        $userIndication->counter_id = $userCounter->id;
                        if ($userIndication->save()) {
                            $indtext = "initial_indications added \n";
                        }
                    } else {
                        
                        $indications->indications = $row['initial_indications'];
                        $indications->counter_id = $userCounter->id;
                        if ($indications->save()) {
                            $indtext = "initial_indications updated \n";
                        }
                    }

                    echo $indtext;
                }else
                    {
                     echo 'counter' . $row['serial_number'].' not saved ('.$lineIndex.' line), becouse'."/n";
                        print_r($userCounter->getErrors());
                    }
                ;
                    $lineIndex++;
            }
            
            echo $addedCount." counters added \n";
            echo $updatedCount.' counters updated';
            
            fclose($handle);
        }
    }

    public function actionInitialToIndication($user_modem_id) {
        $userModem = Modem::findOne(['serial_number' => $user_modem_id]);

        foreach ($userModem->counters as $counter) {

            if (!$counter->indication) {
                $indication = new Indication();

                $indication->indications = $counter->initial_indications;
                $indication->counter_id =  $counter->serial_number;
                if ($indication->save()) {
                    echo "counter ".$counter->serial_number."  initial_indications added \n";
                }
            }
        }
    }

}
