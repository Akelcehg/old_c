<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\commands;

/**
 * Description of CounterIndicationController
 *
 * @author alks
 */
use Yii;
use app\models\Indication;
use app\models\CounterModel;
use app\models\Counter;
use yii\console\Controller;
use yii\db\Query;

class CounterIndicationController extends Controller {

    //put your code here

    public function actionRecalculateIndication($serialNumber, $dump = true) {

        if($dump)
            {
                $dumper = Yii::$app->dumper;
                $bk_file = 'recalc db dumps\RECALC_DUMP-'.date('YmdHis').'.sql';
                $fh = fopen($bk_file, 'w') or die("can't open file");
                fwrite($fh, $dumper->getDump(FALSE));
                fclose($fh);
            }





        if (!empty($serialNumber)) {

            $indications = UserIndications::find()->where(['user_counter_id' => $serialNumber])->all();

            $firstIndication = UserIndications::find()->where(['user_counter_id' => $serialNumber])->orderBy('created_at')->one();

            $counter= UserCounters::findOne([['serial_number' => $serialNumber]]);

            $counterModel=  CounterModel::findOne(['id'=>$counter->counter_model]);


            if ($indications) {

                $impulsePreviousIndication=$firstIndication->impuls;
                $indicationPreviousIndication=$firstIndication->indications;

                foreach ($indications as $indication)
                    {
                        $indication->indications=$indicationPreviousIndication+(($indication->impuls-$impulsePreviousIndication)*$counterModel->rate);

                        if($indication->save())
                            {
                                $impulsePreviousIndication=$indication->impuls;
                                $indicationPreviousIndication=$indication->indications;
                            }
                            else
                                {
                                    echo 'error serial number: '.$serialNumber.' indication id : '.$indication->id;
                                    break;
                                }


                    }
                $counter->last_indications=  $indicationPreviousIndication ;
                $counter->save();
            } else {
                echo 'need enter valid serial number!';
            }
        } else {
            echo 'need enter serial number!';
        }


    }

    public function actionRecalculateIndicationFromIndication($serialNumber,$indication,$dump = true) {

        if($dump)
        {
            $dumper = Yii::$app->dumper;
            $bk_file = 'recalc db dumps\RECALC_DUMP-'.date('YmdHis').'.sql';
            $fh = fopen($bk_file, 'w') or die("can't open file");
            fwrite($fh, $dumper->getDump(FALSE));
            fclose($fh);
        }





        if (!empty($counter_id)) {

            $indications = UserIndications::find()
                ->where(['user_counter_id' => $serialNumber])
                ->andWhere('id >:id',['id'=>$indication])
                ->all();

            $firstIndication = UserIndications::find()
                ->where(['user_counter_id' => $serialNumber])
                ->andWhere(['id'=>$indication])
                ->orderBy('created_at')->one();

            $counter= UserCounters::findOne([['serial_number' => $serialNumber]]);

            $counterModel=  CounterModel::findOne(['id'=>$counter->counter_model]);


            if ($indications) {

                $impulsePreviousIndication=$firstIndication->impuls;
                $indicationPreviousIndication=$firstIndication->indications;

                foreach ($indications as $indication)
                {
                    $indication->indications=$indicationPreviousIndication+(($indication->impuls-$impulsePreviousIndication)*$counterModel->rate);

                    if($indication->save())
                    {
                        $impulsePreviousIndication=$indication->impuls;
                        $indicationPreviousIndication=$indication->indications;
                    }
                    else
                    {
                        echo 'error serial number: '.$serialNumber.' indication id : '.$indication->id;
                        break;
                    }


                }
                $counter->last_indications=  $indicationPreviousIndication ;
                $counter->save();
            } else {
                echo 'need enter valid serial number!';
            }
        } else {
            echo 'need enter serial number!';
        }
    }

    public function actionRecalculateAllIndicationFromDate($date,$dump = true)
    {

        if ($dump) {
            $dumper = Yii::$app->dumper;
            $bk_file = 'recalc db dumps\RECALC_DUMP-' . date('YmdHis') . '.sql';
            $fh = fopen($bk_file, 'w') or die("can't open file");
            fwrite($fh, $dumper->getDump(FALSE));
            fclose($fh);
        }

        $counters = Counter::find()->where("updated_at >:updated_at",[":updated_at"=>"2016-02-03 00:00:00"])->all();

        foreach ($counters as $counter) {



            if ($counter) {

                $firstIndication = Indication::find()->where("created_at >:created_at", [":created_at" => "2016-02-03 00:00:00"])->andWhere(['counter_id' => $counter->id])->orderBy(["created_at" => SORT_ASC])->one();
                if ($firstIndication) {
                    $indications = Indication::find()
                        ->where(['counter_id' => $counter->id])
                        ->andWhere('id >:id', [':id' => $firstIndication->id])
                        ->all();


                    //$counter = Counter::findOne([['id' => $counter_id]]);

                    $counterModel = CounterModel::findOne(['id' => $counter->counter_model]);


                    if ($indications) {

                        $impulsePreviousIndication = $firstIndication->impulse->impulse;
                        $indicationPreviousIndication = $firstIndication->indications;

                        foreach ($indications as $indication) {
                            $indication->indications = $indicationPreviousIndication + (($indication->impulse->impulse - $impulsePreviousIndication) * $counterModel->rate);

                            if ($indication->save()) {
                                $impulsePreviousIndication = $indication->impulse->impulse;
                                $indicationPreviousIndication = $indication->indications;
                            } else {
                                echo 'error counter number: ' . $counter->id . ' indication id : ' . $indication->id;
                                break;
                            }


                        }
                        $counter->last_indications = $indicationPreviousIndication;
                        $counter->save();
                    } else {
                        echo 'need enter valid serial number!';
                    }
                } else {
                    echo 'not found indication';
                }
            }
        }
    }


    /**
     * Zip Indications - delete all indications for every day - except first indication
     * @author Igor S <igor.skakovskiy@sferastudios.com>
     *
     * @param integer $counterId Counter ID
     * @param bool|true $onlyIndication
     * @throws \Exception
     */
    public function actionZip($counterId, $onlyIndication = true)
    {
        $uniqueIndicationQuery = (new Query())
            ->distinct('indication_date')
            ->select('id, DATE(created_at) as indication_date')
            ->from(Indication::tableName())
            ->andWhere([
                'counter_id' => $counterId
            ]);

        foreach($uniqueIndicationQuery->each() as $uniqueIndication) {
            if ($uniqueIndication['indication_date'] == date("Y-m-d"))
                continue;

            $dayIndicationList = Indication::find()
                ->andWhere([
                    'counter_id' => $counterId
                ])
                ->andWhere("DATE(created_at) = :date", [
                    ":date" => $uniqueIndication['indication_date']
                ])
                ->orderBy('created_at')
                ->all();

            for($i = 0; $i < count($dayIndicationList); $i++) {
                if ($i == 0) continue;
                $dayIndicationList[$i]->delete();
            }
        }

    }

}
