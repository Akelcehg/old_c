<?php
/**
 * Created by PhpStorm.
 * User: alks
 * Date: 13.02.2016
 * Time: 23:39
 */
namespace app\components;

use app\models\CommandConveyor;
use app\models\CorrectorToCounter;
use app\models\FloutechCommands;

class FlouTechCommandGenerator extends \yii\base\Component
{
    public $header = "AA";
    public $counter_id;
    public $contract_hour =9;
  //  public $command;


    public function IntToByteString($int)
    {
        if ($int < 16) {
            return "0" . dechex($int);
        } else {
            return dechex($int);
        }
    }


    public function addStep($a,$b)
    {
        if(($a==0)or($a==255)){
            $a=$b;
        }else{
            $a+=64;
        }

        return $a;
    }







    public function dayReportGenerate($year,$month,$day){

        $correctorInfo = CorrectorToCounter::find()->where(['id' => $this->counter_id])->one();

        $dt= new \DateTime($year."-".$month."-".$day);


        $dt->add(new \DateInterval('PT'.$this->contract_hour.'H'));

        $dn=new \DateTime($year."-".$month."-".$day);
        $dn->add(new \DateInterval('PT'.$this->contract_hour.'H'));
        $dn->add(new \DateInterval('PT23H'));

        $monhex = $this->addStep($monhex=0,$dt->format("n"));

        $comConv=new CommandConveyor();
        $comConv->modem_id=$correctorInfo->modem_id;
        $comConv->command=strtoupper($this->generateFloutechCommand($correctorInfo->corrector_id, 28, [1 => "01", 2 =>$monhex .":". $dt->format("d").":".$dt->format("y"), 3 => $this->addStep($monhex=0,$dt->format("n")) .":". $dt->format("d") .":". $dt->format("y")]));
        $comConv->status="ACTIVE";
        $comConv->command_type=2;
        $comConv->save();


        for($h=0;$h<=24;$h=$h+7) {

            $monhex = $this->addStep($monhex,$dt->format("n"));

            //echo $this->IntToByteString($dt->format("d"))."-".$this->IntToByteString($dt->format("H")) ."\n";
            $comConv=new CommandConveyor();
            $comConv->modem_id=$correctorInfo->modem_id;
            $comConv->command=strtoupper($this->generateFloutechCommand($correctorInfo->corrector_id, 25, [1 => "01", 2 => $monhex .":". $dt->format("d").":".$dt->format("y"), 3 => $dt->format("G"), 4 => $dn->format("n") .":". $dn->format("d") .":". $dn->format("y"), 5 =>$this->contract_hour-1]));
            $comConv->status="ACTIVE";
            $comConv->command_type=2;
            $comConv->save();

            $dt->add(new \DateInterval('PT7H'));

        }

    }


    public function oneDayDataReportGenerate($year,$month,$day){

    $correctorInfo = CorrectorToCounter::find()->where(['id' => $this->counter_id])->one();

    $dt= new \DateTime($year."-".$month."-".$day);

        $comConv=new CommandConveyor();
        $comConv->modem_id=$correctorInfo->modem_id;
        $comConv->command=strtoupper($this->generateFloutechCommand($correctorInfo->corrector_id, 28, [1 => "01", 2 =>$dt->format("n").":".$dt->format("d") .":". $dt->format("y"), 3 =>$dt->format("n") .":". $dt->format("d") .":".$dt->format("y")]));
        $comConv->status="ACTIVE";
        $comConv->command_type=2;
        $comConv->save();

}



    public function hourReportGenerate($year,$month,$day,$hour){

        $correctorInfo = CorrectorToCounter::find()->where(['id' => $this->counter_id])->one();

        $dt= new \DateTime($year."-".$month."-".$day);
        $dt->add(new \DateInterval('PT'.$hour.'H'));

        $dn=new \DateTime($year."-".$month."-".$day);
        $dn->add(new \DateInterval('PT'.$hour.'H'));
        $dn->add(new \DateInterval('PT1H'));

        $comConv=new CommandConveyor();
        $comConv->modem_id=$correctorInfo->modem_id;
        $comConv->command=strtoupper($this->generateFloutechCommand($correctorInfo->corrector_id, 25, [1 => "01", 2 =>$dt->format("n") .":".$dt->format("d") .":". $dt->format("y"), 3 =>$dt->format("G"), 4 => $dn->format("n") .":". $dn->format("d") .":".$dn->format("y"), 5 =>$dn->format("G")]));
        $comConv->status="ACTIVE";
        $comConv->command_type=2;
        $comConv->save();

    }

    public function everyHourReportGenerate(){

        $dt = new \DateTime();
        $dt->sub(new \DateInterval('PT1H'));

        $this->hourReportGenerate($dt->format("Y"),$dt->format("m"),$dt->format("d"),$dt->format("H"));

    }

    public function dayReportForMonthGenerate($year,$month,$beginDay=1,$endDay=31)
    {

        for ($i=$beginDay;$i<=$endDay;$i++){

            $this->dayReportGenerate($year,$month,$i);

        }

    }

    public function CustomCommandGenerate($command,$commVarArr=[]){

        $correctorInfo = CorrectorToCounter::find()->where(['id' => $this->counter_id])->one();

        $comConv=new CommandConveyor();
        $comConv->modem_id=$correctorInfo->modem_id;
        $comConv->command=strtoupper($this->generateFloutechCommand($correctorInfo->corrector_id, $command,$commVarArr));
        $comConv->status="ACTIVE";
        $comConv->command_type=2;
        $comConv->save();

    }



    public function MomentDataCommand($commVarArr=[]){

        $this->CustomCommandGenerate(04,$commVarArr);

    }

    public function DateTimeCommand(){

        $this->CustomCommandGenerate("1D");

    }

    public function InterventionCommand($commVarArr=[]){

        $this->CustomCommandGenerate(11,$commVarArr);


    }
    public function DiagnosticCommand($commVarArr=[]){

        $this->CustomCommandGenerate(12,$commVarArr);


    }

    public function EmergencySignCommand($commVarArr=[]){

        $this->CustomCommandGenerate(21,$commVarArr);


    }

    public function SpecCommand(){

        $this->CustomCommandGenerate(9);
    }

    public function EmergensySign($year,$month,$day){

        $correctorInfo = CorrectorToCounter::find()->where(['id' => $this->counter_id])->one();

        $dt= new \DateTime($year."-".$month."-".$day);
        $dn= new \DateTime($year."-".$month."-".$day);
        $di=new \DateInterval("P1D");
        $dn->add($di);


        $this->EmergencySignCommand([1=>$correctorInfo->branch_id,2=>0,3=>$dt->format("n") .":".$dt->format("d") .":". $dt->format("y"),4=>$dn->format("n") .":". $dn->format("d") .":".$dn->format("y")]);

    }

    public function DayDiagnosticCommand($year,$month,$day){

        $correctorInfo = CorrectorToCounter::find()->where(['id' => $this->counter_id])->one();

        $dt= new \DateTime($year."-".$month."-".$day);
        $dn= new \DateTime($year."-".$month."-".$day);
        $di=new \DateInterval("P1D");
        $dn->add($di);


        $this->DiagnosticCommand([1=>$correctorInfo->branch_id,2=>0,3=>$dt->format("n") .":".$dt->format("d") .":". $dt->format("y"),4=>"9:0:0",5=>$dn->format("n") .":". $dn->format("d") .":".$dn->format("y"),6=>"9:0:0"]);

    }

    public function Day4DiagnosticCommand($year,$month,$day){

        $correctorInfo = CorrectorToCounter::find()->where(['id' => $this->counter_id])->one();

        $dt= new \DateTime($year."-".$month."-".$day);
        $dn= new \DateTime($year."-".$month."-".$day);
        $di=new \DateInterval("P4D");
        $dn->add($di);


        $this->DiagnosticCommand([1=>$correctorInfo->branch_id,2=>0,3=>$dt->format("n") .":".$dt->format("d") .":". $dt->format("y"),4=>"9:0:0",5=>$dn->format("n") .":". $dn->format("d") .":".$dn->format("y"),6=>"9:0:0"]);

    }

    public function DayInterventionCommand($year,$month,$day){

        $correctorInfo = CorrectorToCounter::find()->where(['id' => $this->counter_id])->one();

        $dt= new \DateTime($year."-".$month."-".$day);
        $dn= new \DateTime($year."-".$month."-".$day);
        $di=new \DateInterval("P1D");
        $dn->add($di);


        $this->InterventionCommand([1=>$correctorInfo->branch_id,2=>0,3=>$dt->format("n") .":".$dt->format("d") .":". $dt->format("y"),4=>"9:0:0",5=>$dn->format("n") .":". $dn->format("d") .":".$dn->format("y"),6=>"9:0:0"]);

    }



    public function DayFullReportGenerate($year,$month,$day){

        $correctorInfo = CorrectorToCounter::find()->where(['id' => $this->counter_id])->one();

        $this->CustomCommandGenerate(22,[]);
        $this->DateTimeCommand();
        $this->MomentDataCommand([1=>$correctorInfo->branch_id]);
        $this->dayReportGenerate($year,$month,$day);
        $this->DayInterventionCommand($year,$month,$day);
        $this->EmergensySign($year,$month,$day);
        $this->DayDiagnosticCommand($year,$month,$day);
        $this->GetBalance();

    }


    public function GetBalance(){
        $correctorInfo = CorrectorToCounter::find()->where(['id' => $this->counter_id])->one();

        $comConv=new CommandConveyor();
        $comConv->modem_id=$correctorInfo->modem_id;
        $comConv->command='AT+CUSD=1,"*111#";';
        $comConv->status="ACTIVE";
        $comConv->command_type=1;
        $comConv->save();
        
    }



        public function MonthFullReportGenerate($year,$month,$beginDay=1,$endDay=31)
        {
            if (($year==date("y"))and($month==date("n")))
            {
                $dt=new \DateTime();
                $di=new \DateInterval("P1D");
                $di->invert=1;
                $dt->add($di);

                $endDay=$dt->format("j");
            }

            for ($i=$beginDay;$i<=$endDay;$i++){

                $this->DayFullReportGenerate($year,$month,$i);

            }

        }

    public function MonthDayReportGenerate($year,$month,$day,$yearK,$monthK,$dayK){

        $correctorInfo = CorrectorToCounter::find()->where(['id' => $this->counter_id])->one();



        $dt= new \DateTime($year."-".$month."-".$day);
        $dn=new \DateTime($year."-".$month."-".$day);
        $de=new \DateTime($yearK."-".$monthK."-".$dayK);

        $di=$de->diff($dt);
        $monhex = $this->addStep($monhex=0,$dt->format("n"));


        //echo  $di->days;

        for($h=0;$h<=$di->days;$h=$h+4) {

            $monhex = $this->addStep($monhex,$dt->format("n"));
            $dn->add(new \DateInterval('P4D'));

           // echo  $this->generateFloutechCommand($correctorInfo->corrector_id, 28, [1 => "01", 2 =>$monhex .":". $dt->format("d").":".$dt->format("y"), 3 => $this->addStep($monhex=0,$dn->format("n")) .":". $dn->format("d") .":". $dn->format("y")])."\n";
            //echo $this->IntToByteString($dt->format("d"))."-".$this->IntToByteString($dt->format("H")) ."\n";
            $comConv=new CommandConveyor();
            $comConv->modem_id=$correctorInfo->modem_id;
            $comConv->command=strtoupper($this->generateFloutechCommand($correctorInfo->corrector_id, 28, [1 => "01", 2 =>$monhex .":". $dt->format("d").":".$dt->format("y"), 3 => $this->addStep($monhex=0,$dn->format("n")) .":". $dn->format("d") .":". $dn->format("y")]));
            $comConv->status="ACTIVE";
            $comConv->command_type=2;
            $comConv->save();


            $this->DiagnosticCommand([1=>$correctorInfo->branch_id,2=>0,3=>$dt->format("n") .":".$dt->format("d") .":". $dt->format("y"),4=>"9:0:0",5=>$dn->format("n") .":". $dn->format("d") .":".$dn->format("y"),6=>"9:0:0"]);
            $this->InterventionCommand([1=>$correctorInfo->branch_id,2=>0,3=>$dt->format("n") .":".$dt->format("d") .":". $dt->format("y"),4=>"9:0:0",5=>$dn->format("n") .":". $dn->format("d") .":".$dn->format("y"),6=>"9:0:0"]);
            $this->EmergencySignCommand([1=>$correctorInfo->branch_id,2=>0,3=>$dt->format("n") .":".$dt->format("d") .":". $dt->format("y"),4=>$dn->format("n") .":". $dn->format("d") .":".$dn->format("y")]);


            $dt->add(new \DateInterval('P4D'));

        }

    }


    public function MonthDayReportGenerate20($year,$month,$day,$yearK,$monthK,$dayK){

        $correctorInfo = CorrectorToCounter::find()->where(['id' => $this->counter_id])->one();



        $dt=new \DateTime($year."-".$month."-".$day);
        $dn=new \DateTime($year."-".$month."-".$day);
        $de=new \DateTime($yearK."-".$monthK."-".$dayK);

        $di=$de->diff($dt);


        $dn->add(new \DateInterval('P4D'));
        $monhex=$dt->format("n");

        for($h=0;$h<=$di->days;$h=$h+4) {

            $monhex = $this->addStep($monhex,$dt->format("n"));
            //$dc=$dt->format("j");


            if($monhex>255)
            {
                $monhex=$dt->format("n");
            }




            // echo  $this->generateFloutechCommand($correctorInfo->corrector_id, 28, [1 => "01", 2 =>$monhex .":". $dt->format("d").":".$dt->format("y"), 3 => $this->addStep($monhex=0,$dn->format("n")) .":". $dn->format("d") .":". $dn->format("y")])."\n";
            //echo $this->IntToByteString($dt->format("d"))."-".$this->IntToByteString($dt->format("H")) ."\n";
            $comConv=new CommandConveyor();
            $comConv->modem_id=$correctorInfo->modem_id;
            $comConv->command=strtoupper($this->generateFloutechCommand($correctorInfo->corrector_id, 20, [1 => "01",2=>$monhex .":".$dt->format("j").":".$dt->format("y"), 3 =>$dn->format("n") .":". $dn->format("j") .":". $dn->format("y")]));
            $comConv->status="ACTIVE";
            $comConv->command_type=2;
            $comConv->save();




            $dt->add(new \DateInterval('P4D'));
            $dn->add(new \DateInterval('P4D'));

        }

    }





    public function generateFloutechCommand($corrector_id, $command, $comandVariables)
    {
        $cc=CorrectorToCounter::findOne(["corrector_id"=>$corrector_id]);

        $commandInfo = FloutechCommands::find()->where(["command" => $command])->one();

        $zag = $this->header;
        $zag .= $this->IntToByteString($corrector_id);

        $query= $commandInfo->command;

        if((($cc->prog=="MConCore")or($cc->prog=="ConCore1"))and($commandInfo->command=="28")) {

            $query = "24";
        }

        for ($i = 1; $i <= $commandInfo->variables_count; $i++) {

            foreach ($commandInfo->variables as $variable) {

                if ($variable->order == $i) {



                    $varArr=explode(":",$comandVariables[$i]);

                    foreach($varArr as $var) {
                        $query .= $this->IntToByteString($var);

                        if(($cc->prog=="MConCore")and($variable->order==1)and(!in_array($commandInfo->command,["11","12"]))){

                            $query .= $this->IntToByteString(0);

                        }
                    }

                }

            }

        }

        $q=strlen($zag . $query) / 2 + 3 ;



        if ($q< 16) {
            $len = "0" . dechex($q);
        } else {
            $len = dechex($q);
        }


        return $zag . $len . $query;

    }



}