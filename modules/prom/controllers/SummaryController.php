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
use app\models\SummaryReport;
use app\modules\prom\components\DiagnosticWidget;
use app\modules\prom\components\EmergencySituationWidget;
use app\modules\prom\components\ForcedPaymentButton;
use app\modules\prom\components\ForcedReportButton;
use app\modules\prom\components\InterventionWidget;
use kartik\mpdf\Pdf;
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

class SummaryController extends Controller
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

                            ],
                            'allow' => true,
                            //Admin and prom admin can  make actions to addresses
                            'roles' => ['GRS'],
                        ]
                        ,
                        [
                            'actions' => [
                                'summary',
                                'summaryindex',
                                'savesummary',
                                'viewsummary',
                                'loadsummary',
                                'editsummary',

                            ],
                            'allow' => true,
                            //Admin and prom admin can  make actions to addresses
                            'roles' => ['summary'],
                        ]
                    ]
                ,
            ],
        ];
    }

    public function actionIndex()
    {

        $globalChartSettings = [
            'responsive' => 'true',
            'animation' => 'false',
            'showTooltips' => 'false',
            'tooltipTemplate' => '"<%= value %> куб."'];


        echo $this->render('index', ['globalChartSettings' => $globalChartSettings]);
    }


    public function actionEditsummary()
    {
        $id = Yii::$app->request->get('id', false);
        if (!$id) {
            $id = Yii::$app->request->post('id', false);
        }

        if ($id) {
            $summary = SummaryReport::findOne($id);
        } else {
            $summary = new SummaryReport();
        }

        echo $this->render('editsummary', ['summary' => $summary]);
    }

    public function actionSummary()
    {

        if (Yii::$app->request->isPost) {
            $Post = [];
            $Post['GRS'] = round(Yii::$app->request->post('grs', 0), 2);
            $Post['Prom'] = round(Yii::$app->request->post('prom', 0), 2);
            $Post['legal_entity'] = round(Yii::$app->request->post('legal_entity', 0), 2);
            $Post['house_metering'] = round(Yii::$app->request->post('house_metering', 0), 2);
            $Post['individual'] = round(Yii::$app->request->post('individual', 0), 2);
            $Post['All'] = Yii::$app->request->post('all', 0);


            $content = Yii::$app->controller->renderPartial('_summary2', ['post' => $Post]);
            $this->PDFOutput("svodka.pdf", $content);
        }
        echo $this->render('summary');
    }

    public function actionLoadsummary($id)
    {

        $summary = SummaryReport::findOne($id);
        $content = Yii::$app->controller->renderPartial('_summary2', ['post' => $summary]);
        $this->PDFOutput("svodka.pdf", $content);

    }

    public function actionViewsummary($id)
    {
        $summary = SummaryReport::findOne($id);
        echo $this->render('viewsummary', ['summary' => $summary]);
    }

    public function actionSummaryindex()
    {
        $beginDate = Yii::$app->request->get('beginDate', date('Y-m-1'));

        $dt = new \DateTime($beginDate);
        $dt->add(new \DateInterval("P1M"));
        $endDate = $dt->format("Y-m-d");

        $summary = SummaryReport::find()
            ->where(['and', ['<', 'created_at', $endDate], ['>', 'created_at', $beginDate]]);
        $firstdate = SummaryReport::find()->orderBy(['created_at' => SORT_ASC])->one();
        if (empty($firstdate)) {
            $firstdate = new SummaryReport();
            $firstdate->created_at = date("Y-m-d");
        }
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $summary,
            "pagination" => false,
            'sort' => new \yii\data\Sort([
                'attributes' => [
                    'id',
                    'created_at',
                ],

                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]

            ]),
        ]);

        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('summaryindexO', ['dataProvider' => $dataProvider, 'firstdate' => $firstdate->created_at]);
        } else {
            return $this->render('summaryindex', ['dataProvider' => $dataProvider, 'firstdate' => $firstdate->created_at]);
        }


    }

    public function actionSavesummary()
    {
        $id = Yii::$app->request->get('id', false);
        if (!$id) {
            $id = Yii::$app->request->post('id', false);
        }

        if ($id) {
            $summary = SummaryReport::findOne($id);
        } else {
            $summary = new SummaryReport();
        }

        $summary->grs = round(Yii::$app->request->post('grs', 0), 2);
        $summary->prom = round(Yii::$app->request->post('prom', 0), 2);
        $summary->legal_entity = round(Yii::$app->request->post('legal_entity', 0), 2);
        $summary->house_metering = round(Yii::$app->request->post('house_metering', 0), 2);
        $summary->individual = round(Yii::$app->request->post('individual', 0), 2);
        $summary->all = round(Yii::$app->request->post('all', 0), 2);
        if ($summary->save()) {
            $this->redirect("summaryindex");
        } else {
            $this->goBack();
        }
    }



    private function PDFOutput($filename = "output.pdf", $content)
    {

        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,

            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'filename' => $filename,
            // 'defaultFont'=>,

            // stream to browser inline
            'destination' => Pdf::DEST_DOWNLOAD,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px} ; div{font-family:Arial;color:#000000}',
            // set mPDF properties on the fly
            'options' => ['title' => 'CУТОЧНЫЙ ОТЧЕТ'],

            // call mPDF methods on the fly
            'methods' => ['SetFooter' => ["Лист {PAGENO} из 2", "RIGTH",]

            ]
        ]);

        // return the pdf output as per the destination setting


        $pdf->getApi()->defaultfooterline = 0;


        return $pdf->render();

    }

}