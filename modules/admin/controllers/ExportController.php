<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 04.01.16
 * Time: 11:44
 */

namespace app\modules\admin\controllers;
use app\components\Alerts;
use app\modules\admin\components\Counter;
use Yii;
use app\components\CounterQuery;



use yii\web\Controller;

class ExportController extends Controller
{
    //export in csv
    const  EXPORT_COLUMN_GASOILNE=['account','serial_number','last_indications','updated_at'];

    //export in xls
    const  EXPORT_COLUMN_EXCEL=[
        'fulladdress',
        'flat',
        'modem_id',
        'rmodule_id',
        'serial_number',
        'account',
        'last_indications',
        'flatindications',
];



    //export in dbf
    ///const  EXPORT_COLUMN_1C=['account','serial_number','last_indications','updated_at'];



    public function actionAjaxuploadimage()
    {
        $img = Yii::$app->request->post('image', false);

        define('UPLOAD_DIR', 'images/');

        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $filename = uniqid();
        $file = UPLOAD_DIR . $filename . '.png';
        file_put_contents($file, $data);
        return $filename;
    }

    public function actionExportimage($temp)
    {
        $file = 'images/' . $temp . '.png';

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

    protected function timestampToDBF($timestamp)
    {
        $timestampArray = explode(' ', $timestamp);
        $yearMounthDayArray = explode('-', $timestampArray[0]);
        return $yearMounthDayArray[0] . $yearMounthDayArray[1] . $yearMounthDayArray[2];
    }



    public function actionAjaxlistcounterbyfile()
    {
        $counterList = new Counter();
        $counterList ->CounterList();
        $counter = $counterList->getModel()
            ->andWhere(['counters.user_type' => 'individual'])
            ->andWhere('counters.account != ""')
            ->all();

        //$this->lastIndicationsToDBF($counter);
       $title = $this->CountersToExcel($counter,self::EXPORT_COLUMN_GASOILNE,'csv');
        return $title;
    }

    protected function CountersToExcel($counters,$columns,$fileFormat){

        //print_r($columns);

        $objPHPExcel = new \PHPExcel();

        $objPHPExcel->getProperties()
                ->setCreator('Aser')
                ->setTitle('Export Counter');

        $excelSheet = $objPHPExcel->getSheet(0);

        for ($col = 0; $col < count($columns); $col++) {
            $excelSheet->getColumnDimension(chr($col+65))->setAutoSize(true);
        }
        $titles=[];
        $counterModel = new \app\models\Counter();
        foreach($columns as $column){
            $titles[]=$counterModel->attributeLabels()[$column];
        }

        $excelSheet->fromArray($titles, 0, 'A1', true);

            $i = 2;
            foreach ($counters as $counter) {


                for ($col = 0; $col < count($columns); $col++) {

                    switch($columns[$col]){
                        case 'user_type':
                            $value=$this->getUserTypeList()[$counter->$columns[$col]];
                            break;

                        default:
                            $value =$counter->$columns[$col];
                    }

                    $excelSheet->SetCellValue(chr($col+65) . $i,$value);

                }


                $i++;
            }

            $title = 'test'. '-' . date('Y-m-d');
            $title = strtr($title, ["\\" => '-', "/" => '-']);
            $objPHPExcel->getActiveSheet()->setTitle('Лист 1');
            //$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
            //$objWriter->save('excel/' . $title . '.xlsx');

            switch($fileFormat){
                case 'xlsx':
                    $writerName='Excel2007';
                    $fileExtension ='.xlsx';
                    break;
                case '.csv':
                    $writerName='CSV';
                    $fileExtension ='.csv';
                    break;
                default:
                    $writerName='CSV';
                    $fileExtension ='.csv';
            }

            $writer2 = \PHPExcel_IOFactory::createWriter($objPHPExcel, $writerName);
            $writer2->setIncludeCharts(true);
            $writer2->save('excel/' .$title.$fileExtension);

            return $title;
    }

    protected function lastIndicationsToDBF($counterArray)
    {


        // "name" Бд

        $dbname = sys_get_temp_dir() . '/' . Yii::$app->user->getId() . '-export-' . date('Y-m-d') . '.dbf';


        echo $dbname;

        // "definition/определение" БД
        $def = [
            ["account", "C", 255],
            ["serial_num", "C", 30],
            ["indication", "N", 8, 3],
            ["updated_at", "D"],
        ];

        $dbase = dbase_create($dbname, $def);


        foreach ($counterArray as $oneCounter) {
            print_r($this->timestampToDBF($oneCounter->updated_at));
            echo '</br>';
            if (!empty($oneCounter->account) and !is_null($oneCounter->serial_number)) {
                dbase_add_record($dbase, [$oneCounter->account, $oneCounter->serial_number, round($oneCounter->last_indications, 3), $this->timestampToDBF($oneCounter->updated_at)]);
            }
        }


        dbase_close($dbase);
    }

    public function actionAjaxlistcounterbyfile1c()
    {
        $counterList = new Counter();
        $counterList ->CounterList();
        $counter = $counterList->getModel()
            ->andWhere(['counters.user_type' => 'legal_entity'])
            ->andWhere('counters.account != ""')
            ->all();

        $this->lastIndicationsToDBF($counter);
        return true;
    }

    public function actionAjaxlistcounterbyexcel()
    {
        $counterList = new Counter();
        $counterList->paginationSize=false;
        $counterList ->CounterList();

        $counter = $counterList->getDataProvider()->getModels();

        //$this->lastIndicationsToDBF($counter);
        $title = $this->CountersToExcel($counter,self::EXPORT_COLUMN_EXCEL,'xlsx');
        return $title;
    }




    public function exportToexcel($adverts)
    {


        $objPHPExcel = new \PHPExcel();


        $objPHPExcel->setActiveSheetIndex(0);


        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);


        $objPHPExcel->getActiveSheet()->SetCellValue('A1', UserCounters::attributeLabels()['serial_number']);
        $objPHPExcel->getActiveSheet()->SetCellValue('B1', UserCounters::attributeLabels()['user_modem_id']);
        //$objPHPExcel->getActiveSheet()->SetCellValue('C1',UserCounters::attributeLabels()['user_id']);
        $objPHPExcel->getActiveSheet()->SetCellValue('C1', UserCounters::attributeLabels()['real_serial_number']);
        $objPHPExcel->getActiveSheet()->SetCellValue('D1', UserCounters::attributeLabels()['account']);
        $objPHPExcel->getActiveSheet()->SetCellValue('E1', UserCounters::attributeLabels()['initial_indications']);
        $objPHPExcel->getActiveSheet()->SetCellValue('F1', UserCounters::attributeLabels()['last_indications']);
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', UserCounters::attributeLabels()['flat']);
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'За период');
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', UserCounters::attributeLabels()['geo_location_id']);
        //$objPHPExcel->getActiveSheet()->SetCellValue('K1',UserCounters::attributeLabels()['counter_model']);
        $objPHPExcel->getActiveSheet()->SetCellValue('J1', UserCounters::attributeLabels()['update_interval']);
        $objPHPExcel->getActiveSheet()->SetCellValue('K1', UserCounters::attributeLabels()['fullname']);
        $objPHPExcel->getActiveSheet()->SetCellValue('L1', UserCounters::attributeLabels()['month_update']);
        $objPHPExcel->getActiveSheet()->SetCellValue('M1', UserCounters::attributeLabels()['updated_at']);
        $i = 2;
        foreach ($adverts as $advert) {

            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $advert->serial_number);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, $advert->user_modem_id);
            //$objPHPExcel->getActiveSheet()->SetCellValue('C'.$i, $advert->user_id);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, $advert->real_serial_number);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, $advert->account);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, $advert->getFirstFlatIndications($advert->serial_number));
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, $advert->getLastFlatIndications($advert->serial_number));
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, $advert->flat);
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . $i, round($advert->getFlatIndicationsForPeriod($advert->serial_number), 3));
            if ($advert->address) {
                $objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, $advert->address->fulladdress);
            } else {
                $objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, '');
            }
            //$objPHPExcel->getActiveSheet()->SetCellValue('K'.$i, $advert->counter_model);
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, $advert->update_interval);
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, $advert->fullname);
            $objPHPExcel->getActiveSheet()->SetCellValue('L' . $i, $advert->month_update);
            $objPHPExcel->getActiveSheet()->SetCellValue('M' . $i, $advert->updated_at);
            $i++;
        }

        $title = $advert->address->region->name . '-' . $advert->address->fulladdress . '-' . date('Y-m-d');
        $title = strtr($title, ["\\" => '-', "/" => '-']);
        $objPHPExcel->getActiveSheet()->setTitle('Лист 1');
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('excel/' . $title . '.xlsx');


        return $title;
    }

    public function actionExportexcel()
    {
        $title = Yii::$app->request->get('title', 0);
        $file = 'excel/' . $title . '.xlsx';

        if (file_exists($file)) {
            // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
            // если этого не сделать файл будет читаться в память полностью!
            if (ob_get_level()) {
                ob_end_clean();
            }
            // заставляем браузер показать окно сохранения файла
            header('Content-Description: File Transfer');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
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

      public function actionExportcsv()
    {
        $title = Yii::$app->request->get('title', 0);
        $file = 'excel/' . $title . '.csv';

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

    public function actionExport()
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



}