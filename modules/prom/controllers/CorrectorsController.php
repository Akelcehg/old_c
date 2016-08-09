<?php

namespace app\modules\prom\controllers;


use app\components\ChartCalc;
use app\components\FlouTechCommandGenerator;
use app\models\CommandConveyor;
use app\models\CorrectorToCounter;
use app\models\DateOptions;
use app\models\DayData;
use app\models\Diagnostic;
use app\models\EmergencySituation;
use app\models\HourData;
use app\models\Intervention;
use app\models\MomentData;
use app\models\SimCard;
use app\models\User;
use app\modules\prom\components\CorrectorComponent;
use app\modules\prom\components\DiagnosticWidget;
use app\modules\prom\components\EmergencySituationWidget;
use app\modules\prom\components\ForcedPaymentButton;
use app\modules\prom\components\ForcedReportButton;
use app\modules\prom\components\InterventionWidget;
use app\modules\prom\components\Limit\Limits;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\Sort;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\HttpException;
use Yii;

class CorrectorsController extends Controller
{
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
                                'index',
                                'indexdebug',
                                'view',
                                'tabs',
                                'ajaxcountertochart',
                                'ajaxreport',
                                'ajaxreportmonth',
                                'ajaxemergency',
                                'ajaxdiagnostic',
                                'ajaxintervention',
                                'ajaxlistcorrectorsbyfile1c',
                                'ajaxlistcorrectorsbyfile1cm',
                                'ajaxlistcorrectorsbycsv',
                                'ajaxcheckforcedpayment',
                                'ajaxcheckforcedreport',
                                'ajaxgetpacket',
                                'ajaxforcemd',
                                'ajaxcheckmd',
                                'emergencylist',
                                'diagnosticlist',
                                'interventionlist',
                                'getemergency',
                                'getdiagnostic',
                                'getintervention',
                                'deleteimage',
                                '1c',
                                'export',
                                'export1c',
                                'exportcsv'
                            ],
                            'allow' => true,
                            //Admin and prom admin can  make actions to addresses
                            'roles' => ['PromAdmin'],
                        ]
                        ,
                        [
                            'actions' => [
                                'index',
                                'tabs',
                            ],
                            'allow' => true,
                            //Admin and prom admin can  make actions to addresses
                            'roles' => ['admin'],
                        ]

                    ]
                ,
            ],
        ];
    }

       public function actionIndexdebug() {

        $type=Yii::$app->request->get('type','prom');

        if ($type=='grs'){
            if(! User::is('GRS')){
                throw new HttpException(403);
            }
        }
        if ($type=='prom'){
            if(! User::is('PromAdmin')){
                $this->redirect('/prom/correctors/tabs');
            }
        }

        $query = CorrectorToCounter::find()
            ->filterWhere(['type'=>$type])
            ->andFilterWhere(['device_type'=>'floutech']);


        $search = \Yii::$app->getRequest()->getQueryParam('search', []);

        if ($search) {
            $query = \Yii::createObject([
                'class' => 'app\components\SearchQueryBuilder',
                'query' => $query,
//                'andFieldsMapping' => [
//                    'q' => 'company',
//                ]
                'closureFilterMapping' => [
                    'q' => function(&$searchQueryBuilder, $value) {
                        $internalQuery = $searchQueryBuilder->getQueryInstance();
                        $internalQuery->andFilterWhere(['LIKE', 'company', $value]);
                    }
                ]
            ])->applySearchConditions($search);
        }


        $dataProvider = new ActiveDataProvider([
            'query' =>$query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);



        $globalChartSettings = [
            'pointDot' => 'false',
            'responsive' =>'false',
            'bezierCurve' => 'false',
            'animation' => 'false',
            'showTooltips'=>'true',
             'scaleBeginAtZero' => 'true',
            'tooltipTemplate' => '"<%= value %> куб."'];


        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'globalChartSettings'=>$globalChartSettings,

        ]);

        echo $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndex() {

        $type=Yii::$app->request->get('type','prom');

        if ($type=='grs'){
            if(! User::is('GRS')){
                throw new HttpException(403);
            }
        }
        if ($type=='prom'){
            if(! User::is('PromAdmin')){
                $this->redirect('/prom/correctors/tabs');
            }
        }

        $query = CorrectorToCounter::find()
            ->filterWhere(['type'=>$type])
            ->andFilterWhere(['device_type'=>'floutech'])
            ->andFilterWhere(['work_status'=>'work']);


        $search = \Yii::$app->getRequest()->getQueryParam('search', []);

        if ($search) {
            $query = \Yii::createObject([
                'class' => 'app\components\SearchQueryBuilder',
                'query' => $query,
//                'andFieldsMapping' => [
//                    'q' => 'company',
//                ]
                'closureFilterMapping' => [
                    'q' => function(&$searchQueryBuilder, $value) {
                        $internalQuery = $searchQueryBuilder->getQueryInstance();
                        $internalQuery->andFilterWhere(['LIKE', 'company', $value]);
                    }
                ]
            ])->applySearchConditions($search);
        }


        $dataProvider = new ActiveDataProvider([
            'query' =>$query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);



        $globalChartSettings = [
            'pointDot' => 'false',
            'responsive' =>'false',
            'bezierCurve' => 'false',
            'animation' => 'false',
            'showTooltips'=>'true',
             'scaleBeginAtZero' => 'true',
            'tooltipTemplate' => '"<%= value %> куб."'];


        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'globalChartSettings'=>$globalChartSettings,

        ]);

        echo $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDeleteimage()
    {
        $file = Yii::$app->request->post('path');

        if (file_exists($file)) {
            unlink($file);
            return 'true';
        } else {
            return 'false';
        }


    }

    public function actionView($id) {
        $path = 'img/prom/' . $id;

        $correctorToCounter = CorrectorToCounter::findOne($id);





        if (!$correctorToCounter) {
            throw new HttpException(404, 'Корректор не найден');
        }

        if ($correctorToCounter->type=='grs'){
            if(! User::is('GRS')){
                throw new HttpException(403);
            }
            $url=Yii::$app->urlManager->createUrl(['/prom/correctors','type'=>'grs']);
        }else{
            $url=Yii::$app->urlManager->createUrl(['/prom/correctors','type'=>'prom']);
        }

        $simCardPost=Yii::$app->request->post('SimCard', FALSE);

        if($simCardPost){
            $simCard = SimCard::find()->where(['modem_id'=>$simCardPost['modem_id']])->one();
            $simCard->setAttributes($simCardPost,false);
            $simCard->save();

        }

        $сorrectorToCounterPost=Yii::$app->request->post('CorrectorToCounter', FALSE);

        if($сorrectorToCounterPost){
            $model = CorrectorToCounter::find()->where(['id'=>$сorrectorToCounterPost['id']])->one();
            $model->setAttributes($сorrectorToCounterPost,false);
            $model->save();

        }

        $limitPost=Yii::$app->request->post('limit', false);


           if(Yii::$app->request->isPost) {

               $limit = new Limits();

               $limit->all_id = $id;
               $limit->year = date('Y');
               $limit->month = date('m');

               if ($limit->GetLimit()) {
                   $limit->id = $limit->GetLimit()->id;
                   $limit->EditLimit($limitPost);


               } else {

                   $limit->CreateLimit($limitPost);


               }
           }



        $limitNMPost=Yii::$app->request->post('limitNM', FALSE);

        if(Yii::$app->request->isPost) {
            $dt=new \DateTime();
            $dt->add(new \DateInterval("P1M"));
            $limit = new Limits();

            $limit->all_id=$id;
            $limit->year=$dt->format('Y');
            $limit->month=$dt->format('m');

            if ($limit->GetNextMonthLimit()) {
                $limit->id=$limit->GetNextMonthLimit()->id;
                $limit->EditLimit($limitNMPost);

            } else {

                $limit->CreateLimit($limitNMPost);

            }

        }

        if (isset(Yii::$app->request->post()['PromImages'])) {
            $promImages = Yii::$app->request->post()['PromImages'];

            $path = 'img/prom/' . $id;

            if (!file_exists($path)) {
                mkdir($path);
            }

            foreach ($promImages as $image) {

                file_put_contents($path . '/' . time() . '.png', fopen($image, 'r'));

            }
        }

        $globalChartSettings = ['animation' => 'false', 'tooltipTemplate' => '"<%= value %> куб."'];


        $cc=CorrectorToCounter::findOne(Yii::$app->request->get('id'));

        $md=MomentData::find()->where(['all_id'=>$cc->id])->orderBy(['created_at'=>SORT_DESC])->one();

        $stg=\app\models\StaticDataGeneral::find()->where(['all_id'=>$cc->id])->one();


        echo $this->render('view', [
            'id'=>$id,
            'correctorToCounter' => $correctorToCounter,
            'globalChartSettings' => $globalChartSettings,
            'imagesArray' => glob($path . '/*.*'),
            'url'=>$url,
            'cc'=>$cc,
            'md'=>$md,
            'stg'=>$stg
        ]);
    }

    public function actionAjaxcountertochart()

    {
        Yii::$app->response->format = 'json';
        $label=[];
        $counter_id = Yii::$app->request->get('counter_id', false);
        if($counter_id){
        $cc=CorrectorToCounter::findOne($counter_id);
        $hd=DayData::find()->where(['all_id'=>$cc->id,'year'=>date("y"),"month"=>date("n")])->orderBy(["day"=>SORT_ASC])->groupBy("day")->all();

        if($hd) {

            for($i=0;$i<date("t");$i++) {


                    if(isset($hd[$i])) {
                        $label[] = [

                            'label' => $hd[$i]->day,
                            'data' => [round($hd[$i]->v_sc, 3)]
                        ];
                    }else{
                        $label[] = [

                            'label' => $i+1,
                            'data' => 0
                        ];

                    }

            }

            return $label;
        }
        else{

            $md=MomentData::find()->where(['all_id'=>$cc->id])->orderBy(['created_at'=>SORT_DESC])->one();

            $label[] = [

                'label' => date("j"),
                'data' => [round($md->vday_sc, 3)]];

            return $label;
        }
        }else{
            return CorrectorComponent::GetCurrentMonthChart();
        }

    }

    public function actionAjaxlistcorrectorsbyfile1cm()
    {
        $filter= Yii::$app->request->get('id');

        if (empty($filter))
            $filter="мак";

        $correctorList=CorrectorToCounter::find()
            ->andFilterWhere(['LIKE', 'company', $filter])
            ->all();

        $this->correctorsToDBFm($correctorList);

        return true;
    }

    public function actionAjaxlistcorrectorsbyfile1c()
    {
        $correctorList=CorrectorToCounter::find()->all();

        $this->correctorsToDBF($correctorList);

        return true;
    }

    public function actionAjaxlistcorrectorsbycsv()
    {
        $filter= Yii::$app->request->get('id');

        if (empty($filter))
            $filter="мак";

        $correctorList=CorrectorToCounter::find()
            ->andFilterWhere(['LIKE', 'company', $filter])
            ->all();

        return strip_tags($this->correctorsToCSV($correctorList));
    }


    protected function correctorsToCSV($correctorList)
    {
        $arr=[['УЕГГ ПС','Населений пункт ПС','№','Назва','Назва','Адреса','Договір','Расход','Дата']];
        foreach ($correctorList as $oneCorrector) {
            //print_r($this->timestampToDBF($oneCorrector->updated_at));
            echo '</br>';

            if (!empty($oneCorrector->contract)) {

               $arr[]=[
                   $oneCorrector->promInfo->uegg_ps,
                   $oneCorrector->promInfo->np,
                   $oneCorrector->promInfo->number_ca,
                   $oneCorrector->promInfo->company,
                   $oneCorrector->promInfo->name,
                   $oneCorrector->promInfo->address,
                   $oneCorrector->promInfo->contract,
                   round($oneCorrector->lastDayData->v_sc+$oneCorrector->lastDayData->vav_sc, 3),
                   $this->timestampToDBF( date('Y-m-d'))
                   ];
            }
        }

       return $this->CSV($arr);
    }

    protected function correctorsToDBFm($correctorList)
    {   // "name" Бд

        $dbname = sys_get_temp_dir() . '/' . Yii::$app->user->getId() . '-export-' . date('Y-m-d') . '.dbf';

        echo $dbname;

        // "definition/определение" БД
        $def = [
            ["uegg ps", "C", 255],
            ["nas. punkt", "C", 255],
            ["number ca", "C", 255],
            ["nazva plat", "C", 255],
            ["nazva dog", "C", 255],
            ["address", "C", 255],
            ["dogovir", "C", 255],
            ["rashod", "N", 12, 3],
            ["date", "D"],
        ];

        $dbase = dbase_create($dbname, $def);

        foreach ($correctorList as $oneCorrector) {
            //print_r($this->timestampToDBF($oneCorrector->updated_at));
            echo '</br>';
            if (!empty($oneCorrector->contract)) {
                dbase_add_record($dbase, [
                    iconv("UTF-8","CP1251",$oneCorrector->promInfo->uegg_ps),
                    iconv("UTF-8","CP1251",$oneCorrector->promInfo->np),
                    $oneCorrector->promInfo->number_ca,
                    iconv("UTF-8","CP1251",$oneCorrector->promInfo->company),
                    iconv("UTF-8","CP1251",$oneCorrector->promInfo->name),
                    iconv("UTF-8","CP1251",$oneCorrector->promInfo->address),
                    iconv("UTF-8","CP1251",$oneCorrector->promInfo->contract),
                    round($oneCorrector->currentMonthConsumption, 3),
                    $this->timestampToDBF( date('Y-m-d'))]);
            }
        }

        dbase_close($dbase);
    }


    protected function correctorsToDBF($correctorList)
    {// "name" Бд

        $dbname = sys_get_temp_dir() . '/' . Yii::$app->user->getId() . '-export-' . date('Y-m-d') . '.dbf';

        echo $dbname;

        // "definition/определение" БД
        $def = [
            ["contract", "C", 255],
            ["consump", "N", 12, 3],
            ["updated_at", "D"],
        ];

        $dbase = dbase_create($dbname, $def);

        foreach ($correctorList as $oneCorrector) {
            //print_r($this->timestampToDBF($oneCorrector->updated_at));
            echo '</br>';
            if (!empty($oneCorrector->contract)) {
                dbase_add_record($dbase, [$oneCorrector->contract,round($oneCorrector->lastDayData->v_sc+$oneCorrector->lastDayData->vav_sc, 3), $this->timestampToDBF($oneCorrector->lastDayData->timestamp)]);
            }
        }

        dbase_close($dbase);
    }

    protected function timestampToDBF($timestamp)
    {
        $timestampArray = explode(' ', $timestamp);

        $yearMounthDayArray = explode('-', $timestampArray[0]);

        return $yearMounthDayArray[0] . $yearMounthDayArray[1] . $yearMounthDayArray[2];
    }

    public function actionExport1c()
    {
        $file = sys_get_temp_dir() . '/' . Yii::$app->user->getId() . '-export-' . date('Y-m-d') . '.dbf';

        if (file_exists($file)) {
            // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
            // если этого не сделать файл будет читаться в память полностью!
            if (ob_get_level()) {
                ob_end_clean();
            }
            // заставляем браузер показать окно сохранения файла
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            // читаем файл и отправляем его пользователю
            readfile($file);

            unlink($file);
            exit;
        }
    }

    public function actionAjaxreport()
    {
       // $cc=CorrectorToCounter::findOne();

        //return \app\modules\prom\components\CorrectorDailyDataComponent::widget(['id' => $cc->id,'year'=>Yii::$app->request->get('year'),"month"=>Yii::$app->request->get('month')]);
        return \app\modules\prom\components\ReportChecker\widgets\CorrectorDailyDataWidget::widget(['id' => Yii::$app->request->get('id'),'date'=>Yii::$app->request->get('year').'-'.Yii::$app->request->get('month')]);

    }



    public function actionAjaxreportmonth()
    {
        $cc=CorrectorToCounter::findOne(Yii::$app->request->get('id'));

        return \app\modules\prom\components\CorrectorMonthDataComponent::widget(['id' => $cc->id,'year'=>Yii::$app->request->get('year')]);


    }

    public function actionAjaxemergency()
    {
        $cc=CorrectorToCounter::findOne(Yii::$app->request->get('id'));

        return \app\modules\prom\components\EmergencySituationWidget::widget(['id'=>$cc->id,'year'=>Yii::$app->request->get('year'),"month"=>Yii::$app->request->get('month')]);

    }

    public function actionAjaxintervention()
    {
        $cc=CorrectorToCounter::findOne(Yii::$app->request->get('id'));

        return \app\modules\prom\components\InterventionWidget::widget(['id'=>$cc->id,'year'=>Yii::$app->request->get('year'),"month"=>Yii::$app->request->get('month')]);

    }

    public function actionAjaxdiagnostic()
    {
        $cc=CorrectorToCounter::findOne(Yii::$app->request->get('id'));

        return \app\modules\prom\components\DiagnosticWidget::widget(['id'=>$cc->id,'year'=>Yii::$app->request->get('year'),"month"=>Yii::$app->request->get('month')]);

    }

    //#exportCounter1C
    public function actionExport() {

        echo $this->render('1c');
    }

    protected function CSV($array){

        //print_r($columns);

        $objPHPExcel = new \PHPExcel();

        $objPHPExcel->getProperties()
            ->setCreator('Aser')
            ->setTitle('Export');

        $excelSheet = $objPHPExcel->getSheet(0);

        $excelSheet->fromArray($array, 0, 'A1', true);

        $writer2 = \PHPExcel_IOFactory::createWriter($objPHPExcel,'CSV');
        $writer2->setDelimiter(";");
        $writer2->setIncludeCharts(true);
        $title=date('Y-m-d');
        $file = sys_get_temp_dir() . '/' . Yii::$app->user->getId() . '-export-' . date('Y-m-d') . '.csv';
        $writer2->save($file);

        return $title;
    }

    public function actionExportcsv()
    {
        $file = sys_get_temp_dir() . '/' . Yii::$app->user->getId() . '-export-' . date('Y-m-d') . '.csv';

        if (file_exists($file)) {
            // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
            // если этого не сделать файл будет читаться в память полностью!
            if (ob_get_level()) {
                ob_end_clean();
            }
            // заставляем браузер показать окно сохранения файла

            header('Content-Description: File Transfer');
            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename=' . basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            // читаем файл и отправляем его пользователю
            readfile($file);

            unlink($file);
            exit;
        }
    }


    const GSM_PREFIX="AT+CUSD=1,\"";


    public function actionAjaxcheckforcedpayment($id)
    {
        $this->layout='onlyGrid';
        $corrector=CorrectorToCounter::find()->where(['id'=>$id])->one();

        $conv=new CommandConveyor();
        $conv->modem_id=$corrector->modem_id;
        $conv->command=self::GSM_PREFIX.$corrector->simCard->request_forced_payment.'"';
        $conv->command_type=1;
        $conv->status="ACTIVE";
        $conv->save();

        return ForcedPaymentButton::widget(['corrector'=>$corrector]);
    }

    public function actionAjaxcheckforcedreport($id)
    {
        $this->layout='onlyGrid';
        $corrector=CorrectorToCounter::find()->where(['id'=>$id])->one();

        $dt=new \DateTime();
        $di=new \DateInterval("P1D");
        $di->invert=1;
        $dt->add($di);

        $dateOptions=DateOptions::find()->where(['all_id'=> $corrector->id])->orderBy(['id'=>SORT_DESC])->one();
        $flouTechComGen = new FlouTechCommandGenerator();
        $flouTechComGen->counter_id= $corrector->id;
        $flouTechComGen->contract_hour= $dateOptions->contract_hour;
        $flouTechComGen->DayFullReportGenerate($dt->format("y"),$dt->format("n"),$dt->format("j"));

        return ForcedReportButton::widget(['corrector'=>$corrector]);
    }

    public function actionAjaxgetpacket($modem_id)
    {
        $this->layout='onlyGrid';
        $corrector=CorrectorToCounter::find()->where(['modem_id'=>$modem_id])->one();

        $conv=new CommandConveyor();
        $conv->modem_id=$corrector->modem_id;
        $conv->command=self::GSM_PREFIX.$corrector->simCard->request_get_packet.'"';
        $conv->command_type=1;
        $conv->status="ACTIVE";
        $conv->save();

        return ForcedPaymentButton::widget(['corrector'=>$corrector]);
    }

    public function actionAjaxforcemd()
    {



        setcookie('forceMD','1',time()+60*20);

        $correctors=CorrectorToCounter::find();
        $corCount= clone $correctors;
        $corCount=$corCount->count();
        foreach( $correctors->all() as $corrector){

            if(!$corrector->isForcedMomentData()) {
                $flouTechComGen = new FlouTechCommandGenerator();
                $flouTechComGen->counter_id = $corrector->id;
                $flouTechComGen->MomentDataCommand([1=>$corrector->branch_id]);
            }

        }

            return Html::tag('span','Обновлено 0 из '.$corCount .Html::img("/images/loading-icon.gif",['style'=>'opacity:0.4']),['id'=>'ForcedMDContainer','style'=>"font-family: 'Open Sans';font-size: 18px;color: white;margin-bottom:0px"]);
    }

    public function actionAjaxcheckmd()
    {
        $countOtv=0;
        $correctors=CorrectorToCounter::find();
        $corCount= clone $correctors;
        $corCount=$corCount->count();
        foreach( $correctors->all() as $corrector){

            if(!$corrector->isForcedMomentData()) {
                $countOtv++;
            }
        }
        if($countOtv==$corCount){
            setcookie('forceMD',null,-1);
            return Html::tag('span','Обновление завершено',['id'=>'ForcedMDContainer','style'=>"font-family: 'Open Sans';font-size: 18px;color: white;margin-bottom:0px"]);
            sleep(5);
            $this->refresh();
        }else{
            setcookie('forceMD','1',time()+60*20);
            return Html::tag('span','Обновлено '.$countOtv.' из '.$corCount.Html::img("/images/loading-icon.gif",['style'=>'opacity:0.4']),['id'=>'ForcedMDContainer','style'=>"font-family: 'Open Sans';font-size: 18px;color: white;margin-bottom:0px"]);
        }
    }


    public function actionEmergencylist(){
        $this->getView()->registerJsFile('/js/corrector/emergencySituation.js',['position'=>2]);

        $type=Yii::$app->request->get('type','prom');

    $emSit=EmergencySituation::find()->where(['>','timestamp',date('Y-m-1')." 00:00:00"])->distinct()->all();
    $output=[];
    foreach($emSit as $emSitone){
        $output[]=$emSitone->all_id;
    }

    $correctors=CorrectorToCounter::find()
        ->where(['in','id',$output])
        ->andFilterWhere(['type'=>$type]);

        $globalChartSettings = [
            'pointDot' => 'false',
            'responsive' =>'false',
            'bezierCurve' => 'false',
            'animation' => 'false',
            'showTooltips'=>'true',
            'scaleBeginAtZero' => 'true',
            'tooltipTemplate' => '"<%= value %> куб."'];

    $output = new ActiveDataProvider([
        'query' => $correctors,
        'pagination' => [
            'pageSize' => 25,
        ],
    ]);

    return $this->render('emergencySituation',['output'=>$output,'globalChartSettings'=>$globalChartSettings]);
}

    public function actionGetemergency()
    {
        $this->layout='onlyGrid';

        $all_id=Yii::$app->request->get('all_id',false);

        return EmergencySituationWidget::widget(['id'=>$all_id]);
    }

    public function actionDiagnosticlist(){
        $this->getView()->registerJsFile('/js/corrector/diagnostic.js',['position'=>2]);
        $type=Yii::$app->request->get('type','prom');
        $emSit=Diagnostic::find()->where(['>','timestamp',date('Y-m-1')." 00:00:00"])->distinct()->all();
        $output=[];
        foreach($emSit as $emSitone){
            $output[]=$emSitone->all_id;
        }

        $correctors=CorrectorToCounter::find()->where(['in','id',$output])->andFilterWhere(['type'=>$type]);
        $globalChartSettings = [
            'pointDot' => 'false',
            'responsive' =>'false',
            'bezierCurve' => 'false',
            'animation' => 'false',
            'showTooltips'=>'true',
            'scaleBeginAtZero' => 'true',
            'tooltipTemplate' => '"<%= value %> куб."'];


        $output = new ActiveDataProvider([
            'query' => $correctors,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        return $this->render('diagnosticList',['output'=>$output,'globalChartSettings'=>$globalChartSettings]);
    }

    public function actionGetdiagnostic()
    {
        $this->layout='onlyGrid';

        $all_id=Yii::$app->request->get('all_id',false);

        return DiagnosticWidget::widget(['id'=>$all_id]);
    }

    public function actionInterventionlist(){
        $this->getView()->registerJsFile('/js/corrector/intervention.js',['position'=>2]);

        $type=Yii::$app->request->get('type','prom');

        $emSit=Intervention::find()->where(['>','timestamp',date('Y-m-1')." 00:00:00"])->distinct()->all();
        $output=[];
        foreach($emSit as $emSitone){
            $output[]=$emSitone->all_id;
        }

        $correctors=CorrectorToCounter::find()->where(['in','id',$output])->andFilterWhere(['type'=>$type]);

        $globalChartSettings = [
            'pointDot' => 'false',
            'responsive' =>'false',
            'bezierCurve' => 'false',
            'animation' => 'false',
            'showTooltips'=>'true',
            'scaleBeginAtZero' => 'true',
            'tooltipTemplate' => '"<%= value %> куб."'];

        $output = new ActiveDataProvider([
            'query' => $correctors,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);

        return $this->render('interventionList',['output'=>$output,'globalChartSettings'=>$globalChartSettings]);
    }

    public function actionGetintervention()
    {
        $this->layout='onlyGrid';

        $all_id=Yii::$app->request->get('all_id',false);

        return InterventionWidget::widget(['id'=>$all_id]);
    }


       public function actionTabs()
    {

        $address = new \app\modules\admin\components\Counter();
        $address->CounterAddressList();

        $globalChartSettings = [
            'pointDot' => 'false',
            'responsive' =>'false',
            'bezierCurve' => 'false',
            'animation' => 'false',
            'showTooltips'=>'true',
            'scaleBeginAtZero' => 'true',
            'tooltipTemplate' => '"<%= value %> куб."'];

        if (Yii::$app->request->isAjax) {

            return $this->renderAjax('indexGridOnly', [
                'dataProvider' => $address->getDataProvider(),
                'address' => $address->getModel(),
                'searchModel' => $address->getSearchModel(),
                'globalChartSettings'=>$globalChartSettings
            ]);
        }


        return $this->render('tabs', [
            'dataProvider' => $address->getDataProvider(),
            'address' => $address->getModel(),
            'searchModel' => $address->getSearchModel(),
            'globalChartSettings'=>$globalChartSettings
        ]);
    }



    /*  function getMonth($a){
          switch($a){
              case 1 :return "января";break;
              case 2 :return "февраля";break;
              case 3 :return "марта";break;
              case 4 :return "апреля";break;
              case 5 :return "мая";break;
              case 6 :return "июня";break;
              case 7 :return "июля";break;
              case 8 :return "августа";break;
              case 9 :return "сентября";break;
              case 10 :return "октября";break;
              case 11 :return "ноября";break;
              case 12 :return "декабря";break;
          }
      }



      public function actionPdf(){

          $counterId=Yii::$app->request->get('counterId');
          $type=Yii::$app->request->get('type','day');

          if(9>date("H")){
              $date=Yii::$app->request->get('date',date("y-n-j",time()-3600*24*2));
          }else{
              $date=Yii::$app->request->get('date',date("y-n-j",time()-3600*24));
          }




          $dateArr=explode(" ",$date);
          $dateDetail=explode("-",$dateArr[0]);

          $counter=\app\models\CorrectorToCounter::find()->where(['id'=>$counterId])->one();

          $dateTime=DateTime::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
          $name=Name::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
          $staticHard=StaticDataHard::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
          $staticSensor=StaticDataSensor::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
          $staticGeneral=StaticDataGeneral::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
          $momentData=MomentData::find()->where(['all_id'=>$counterId])->orderBy(['id'=>SORT_DESC])->one();
          $fTRG= new FlouTechReportGenerator();
          $fTRG->counter_id=$counterId;
          $hourData=$fTRG->dayHourReportGenerate($dateDetail[0],$dateDetail[1],$dateDetail[2]);
          $correctorData=CorrectorsData::findOne(["all_id"=>$counterId]);
          $dayData=$fTRG->monthReportGenerate($dateDetail[0],$dateDetail[1]);

          $intervention=Intervention::find()->where(['all_id'=>$counterId,'month'=>$dateDetail[1],'year'=>$dateDetail[0]])
              ->orderBy(['id'=>SORT_DESC])->all();


          $emSit=EmergencySituation::find()->where('all_id=:all_id AND month=:month AND year=:year ',[':all_id'=>$counterId,':month'=>$dateDetail[1],':year'=>$dateDetail[0]])
              ->orderBy(['id'=>SORT_DESC])->all();

          $emSign=EmergencySign::find()->where('all_id=:all_id AND month=:month AND year=:year ',[':all_id'=>$counterId,':month'=>$dateDetail[1],':year'=>$dateDetail[0]])
              ->orderBy(['id'=>SORT_DESC])->all();


          $diag=Diagnostic::find()->where('all_id=:all_id AND month=:month AND year=:year ',[':all_id'=>$counterId,':month'=>$dateDetail[1],':year'=>$dateDetail[0]])
              ->orderBy(['id'=>SORT_DESC])->all();

          // $dayData=DayData::find()->where(['all_id'=>$counterId,'month'=>$dateDetail[1],'year'=>$dateDetail[0],'day'=>$dateDetail[2]])->orderBy(['id'=>SORT_DESC])->one();


          //$hourData=array_reverse($hourData);

          $content=$this->renderPartial("pdf/header",
              [
                  'type'=>$type,
                  'datetext' => $this->getMonth($dayData[0]->month) . ' 20' . $dayData[0]->year . " года ",
                  'programName' => "ASER ".Yii::$app->params['version'],
                  'time' => $dateTime->time,
                  'date' => $dateTime->date,

              ]
          );




          if($correctorData->measured_value=="Quantity") {
              $content = $this->renderPartial("otchetm", [
                  'counter'=>$counter,
                  'Ksgim'=>$momentData->k,
                  'datetext' => $this->getMonth($dayData[0]->month) . ' 20' . $dayData[0]->year . " года ",
                  'programName' => "ASER ".Yii::$app->params['version'],
                  'time' => $dateTime->time,
                  'date' => $dateTime->date,
                  'correctorName' => $name->corrector_name,
                  'serialNumber' => $staticGeneral->zavod_number,
                  'complexName' => $staticGeneral->complex_name,
                  'tubeName' => $name->tube_name,
                  'poVersion' => $name->version,
                  'metodName' => 'NX19 мод',
                  'counterName' => $staticSensor->counter_name,
                  'metodDimName' => "Счетчик",
                  'contractTime' => "9:00",
                  'density' => round($staticHard->density, 3),
                  'molCO2' => round($staticHard->mol_co2, 2),
                  'pressureType' => "Абсолютное",
                  'molN2' => round($staticHard->mol_n2, 2),
                  'pressureNPI' => round($staticSensor->min_mesurm_lim_p, 4),
                  'pressureVPI' => round($staticSensor->max_mesurm_lim_p, 4),
                  'tempNPI' => round($staticSensor->min_mesurm_lim_t, 4),
                  'tempVPI' => round($staticSensor->max_mesurm_lim_t, 4),
                  'impulseOnM3' => "10.00",
                  'qVPI' => round($staticSensor->max_mesurm_lim_q, 4),
                  'qNPI' => round($staticSensor->min_mesurm_lim_q, 4),
                  'qStop' => round($staticSensor->qstop, 4),
                  'hours' => $hourData,
                  'intervention'=>$intervention,
                  'emsig'=>$emSign,
                  'emsit'=>$emSit,
                  'diag'=>$diag,
                  'daydata'=>$dayData,
                  /*
                  'sumRC' => round($dayData->v_rc, 2),
                  'sumSC' => round($dayData->v_sc, 2),
                  'bezavV' => round($dayData->v_rc, 2),*/
                //'avV' => 0.0,
                /* 'polV' => round($dayData->v_rc, 2),
                  'bezavVsu' => round($dayData->v_sc, 2),
                  'polVsu' => round($dayData->v_sc, 1),
                'avVsu' => 0.0,
                // 'polVsu' => round($dayData->v_sc, 1),
                'timeQem' => 0,
                'pokaz' => round($dayData[count($dayData)-1]->vpokaz_rc, 2),
                'timeEmrg' => 0,


            ]);
        }
        else{

            $content = $this->renderPartial("otchetpm", [
                'counter'=>$counter,
                'Ksgim'=>$momentData->k,
                'datetext' => $this->getMonth($dayData[0]->month) . ' 20' . $dayData[0]->year . " года ",
                'programName' =>"ASER ". Yii::$app->params['version'],
                'time' => $dateTime->time,
                'date' => $dateTime->date,
                'correctorName' => $name->corrector_name,
                'serialNumber' => $staticGeneral->zavod_number,
                'complexName' => $staticGeneral->complex_name,
                'tubeName' => $name->tube_name,
                'poVersion' => $name->version,
                'metodName' => 'ГОСТ8.586-NX19 мод',
                /// 'counterName' => $staticSensor->counter_name,
                'metodDimName' => "Перепад на СУ",
                'contractTime' => "9:00",
                'density' => round($staticHard->density, 3),
                'molCO2' => round($staticHard->mol_co2, 2),
                'pressureType' => "Абсолютное",
                'molN2' => round($staticHard->mol_n2, 2),
                'pressureNPI' => round($staticSensor->min_mesurm_lim_p, 4),
                'pressureVPI' => round($staticSensor->max_mesurm_lim_p, 4),
                'tempNPI' => round($staticSensor->min_mesurm_lim_t, 4),
                'tempVPI' => round($staticSensor->max_mesurm_lim_t, 4),
                'impulseOnM3' => "10.00",
                'qVPI' => round($staticSensor->max_mesurm_lim_q, 4),
                'qNPI' => round($staticSensor->min_mesurm_lim_q, 4),
                'qStop' => round($staticSensor->qstop, 4),
                'hours' => $hourData,
                'intervention'=>$intervention,
                'emsig'=>$emSign,
                'emsit'=>$emSit,
                'diag'=>$diag,
                'daydata'=>$dayData,
                'typeOtb'=>"Угловой",
                'Dtube'=>round($staticHard->d_tube,3),
                'Dsu'=>round($staticHard->d_sug_device,3),
                'otsechka'=>round($staticHard->otsechka,4),
                'sharpness'=>round($staticHard->sharpness,5),
                'a0'=>"10.7",
                'a0su'=>"15.5",
                'a1'=>"12.0",
                'a1su'=>"10.5",
                'a2'=>"0.0",
                'a2su'=>"0.0",
                'Dvyaz'=>"1.06275e-06",
                'perepNPI' => round(10.000, 3),
                'perepVPI' => round(4001.0, 1),
                "mki"=>round($staticHard->control_interval,0),
                "Rzkrug"=>round($staticHard->radius_diafr, 2),
                /* 'sumRC' => round($dayData->v_rc, 2),
                 'sumSC' => round($dayData->v_sc, 2),
                 'bezavV' => round($dayData->v_rc, 2),
                'avV' => 0.0,
                /*  'polV' => round($dayData->v_rc, 2),
                  'bezavVsu' => round($dayData->v_sc, 2),
                  'polVsu' => round($dayData->v_sc, 1),
                'avVsu' => 0.0,
                // 'polVsu' => round($dayData->v_sc, 1),
                'timeQem' => 0,
                'pokaz' => round($dayData[count($dayData)-1]->vpokaz_rc, 2),
                'timeEmrg' => 0,


            ]);
        }



        //echo $result;

        //$content = $this->renderPartial('_reportView');

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,

            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'filename'=>$counter->address->fulladdress."-".$dayData[0]->day . ' ' . $dayData[0]->month . ' 20' . $dayData[0]->year .'.pdf',

            // stream to browser inline
            'destination' => Pdf::DEST_DOWNLOAD,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px} ; div{font-family:Arial}',
            // set mPDF properties on the fly
            'options' => ['title' => 'CУТОЧНЫЙ ОТЧЕТ'],

            // call mPDF methods on the fly
            'methods' => [ 'SetFooter'=>["Лист {PAGENO} из 2","RIGTH",]

            ]
        ]);

        // return the pdf output as per the destination setting


        $pdf->getApi()->defaultfooterline=0;


        return $pdf->render();

    }*/


}