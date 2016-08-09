<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 08.07.16
 * Time: 9:20
 */

namespace app\components\Events;


use app\models\MenuItem;
use app\models\User;
use Yii;
use yii\base\Component;

class UserTracking extends Component
{
    public $id;


    public function getUserAction()
    {


        if ($this->isValidAction()) {

            if ($this->SetIn()) {
                $this->SetOut();
            }


            /*  $user = User::findOne($this->id);
             *  $events = new Events();
             $events->type = 'route';
             $events->region_id = $user->getCurrentUserRegionId($this->id);
             $events->model = $user;
             $events->AddEvent();*/

        }

    }

    private function SetIn()
    {

        $userTracking2 = \app\models\UserTracking::find()
            ->where(['url' => Yii::$app->request->getAbsoluteUrl()])
            ->andWhere(['refferer' => Yii::$app->request->referrer])
            ->andWhere(['time_out' => "0000-00-00 00:00:00"])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        if (!$userTracking2) {

            $userTracking = new \app\models\UserTracking();
            $userTracking->user_id = $this->id;
            $userTracking->time_in = date('Y-m-d H:i:s');
            $userTracking->url = Yii::$app->request->getAbsoluteUrl();
            $userTracking->refferer = is_null(Yii::$app->request->referrer) ? '' : Yii::$app->request->referrer;
            $userTracking->user_action = $this->getTitle();
            $userTracking->created_at = date('Y-m-d H:i:s');

            return $userTracking->save();
        } else {
            return false;
        }

    }

    private function getTitle()
    {

        $menu = MenuItem::find()
            ->where(['like', 'url', Yii::$app->request->getUrl()])
            ->one();

        if ($menu) {
            return $menu->label;
        } else {
            return Yii::$app->request->getUrl();
        }

    }

    private function SetOut()
    {

        $userTracking = \app\models\UserTracking::find()
            ->where(['url' => Yii::$app->request->referrer])
            ->andWhere(['user_id' => $this->id])
            ->andWhere(['time_out' => "0000-00-00 00:00:00"])
            ->orderBy(['id' => SORT_DESC])
            ->one();
        if ($userTracking) {
            $userTracking->time_out = date('Y-m-d H:i:s');
            return $userTracking->save();
        }

    }

    private function isValidAction()
    {

        $user = User::findOne($this->id);

        $urlArray = explode('/', Yii::$app->request->getAbsoluteUrl());

        if (
            $user
            and $user->status == "ACTIVE"
            and !Yii::$app->user->isGuest
            and !Yii::$app->request->isAjax
            and Yii::$app->request->referrer != Yii::$app->request->getAbsoluteUrl()
            and !strripos($urlArray[count($urlArray) - 1], '.')
        ) {
            return true;
        } else {
            return false;
        }

    }

    public function GetLogin()
    {


    }


    public function GetLogout()
    {


        $userTrackings = \app\models\UserTracking::find()
            ->where(['time_out' => "0000-00-00 00:00:00"])
            ->andWhere('time_in < DATE_SUB(NOW(), INTERVAL 20 MINUTE)')
            ->andWhere('time_in > DATE_SUB(NOW(), INTERVAL 1 DAY)')
           ->orderBy(['id' => SORT_DESC])
            ->all();



        foreach ($userTrackings as $ut) {



            $user = User::find()->where(['id' => $ut->user_id])->one();

            $userTracking = \app\models\UserTracking::find()
                ->where(['user_id' => $ut->user_id])
                ->andWhere(['time_out' => "0000-00-00 00:00:00"])
                ->andWhere('time_in < DATE_SUB(NOW(), INTERVAL 20 MINUTE)')
                ->andWhere('time_in > DATE_SUB(NOW(), INTERVAL 1 DAY)')
                ->orderBy(['id' => SORT_DESC])
                ->one();

            print_r($userTracking);

            if ($userTracking) {
                $userTracking->time_out = date('Y-m-d H:i:s');

                if ($userTracking->save()) {

                    $events = new Events();
                    $events->type = 'logout';
                    $events->region_id = $user->getCurrentUserRegionId($ut->user_id);
                    $events->model = $user;
                    $events->AddEvent();

                } else {
                    print_r($userTracking->getErrors());
                }

                return '';

            }


        }
    }
}