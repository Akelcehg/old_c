<?php

namespace app\modules\admin\controllers;
define('SITE_PATH', __DIR__ . '/../../../');
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use yii\web\Controller;
use Yii;
use ZipArchive;

class UpdateController extends Controller
{
    //public $layout = 'smartAdmin';

    public $foldersArray = [
        '../assets',
        '../commands',
        '../components',
        '../config',
        '../controllers',
        '../mail',
        '../migrations',
        '../models',
        '../views',
        '../www'
    ];

    public function actionIndex()
    {
        $username = 'Akelcheg';
        $password = '80ohfsD093jSWeu921';

        //exec(Yii::$app->params['revisionConsolePath'] . " pull -u https://" . $username . ":" . $password . "@bitbucket.org/igorska/counter 2>&1", $out);
        //exec(Yii::$app->params['revisionConsolePath'] . " in https://" . $username . ":" . $password . "@bitbucket.org/igorska/counter 2>&1", $out);
        exec(Yii::$app->params['revisionConsolePath'] . " in 2>&1", $out);

        return $this->render('index', [
            'out' => $out
        ]);
    }

    public function actionExec()
    {
        $username = 'Akelcheg';
        $password = '80ohfsD093jSWeu921';

        //exec(Yii::$app->params['revisionConsolePath'] . " pull -u https://" . $username . ":" . $password . "@bitbucket.org/igorska/counter 2>&1", $out);
        //exec(Yii::$app->params['revisionConsolePath'] . " update 2>&1", $out);

        exec(Yii::$app->params['revisionConsolePath'] . " pull 2>&1", $out);
        exec(Yii::$app->params['revisionConsolePath'] . " update 2>&1", $out);

        exec(SITE_PATH . 'yii migrate --interactive=0 2>&1', $output, $status);


        if (!file_exists('../backup/' . date("Y-m-d") . '-backup')) mkdir('../backup/' . date("Y-m-d") . '-backup');
        $this->backupDb('../backup/db.sql');
        exec('php ../modules/admin/updater.php', $x);

        return $this->render('index', [
            'out' => array_merge($out, $output)
        ]);
    }

    public static function backupDb($filepath, $tables = '*')
    {
        if ($tables == '*') {
            $tables = array();
            $tables = Yii::$app->db->schema->getTableNames();
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }
        $return = '';

        foreach ($tables as $table) {
            $result = Yii::$app->db->createCommand('SELECT * FROM ' . $table)->query();
            //           $return.= 'DROP TABLE IF EXISTS ' . $table . ';';
            $row2 = Yii::$app->db->createCommand('SHOW CREATE TABLE ' . $table)->queryOne();
            $return .= "\n\n" . $row2['Create Table'] . ";\n\n";
            foreach ($result as $row) {
                $return .= 'INSERT INTO ' . $table . ' VALUES(';
                foreach ($row as $data) {
                    $data = addslashes($data);

                    // Updated to preg_replace to suit PHP5.3 +
                    $data = preg_replace("/\n/", "\\n", $data);
                    if (isset($data)) {
                        $return .= '"' . $data . '"';
                    } else {
                        $return .= '""';
                    }
                    $return .= ',';
                }
                $return = substr($return, 0, strlen($return) - 1);
                $return .= ");\n";
            }
            $return .= "\n\n\n";
        }
        //save file
        $handle = fopen($filepath, 'w+');
        fwrite($handle, $return);
        fclose($handle);
    }

}
