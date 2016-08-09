<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 22.03.16
 * Time: 11:35
 */

namespace app\components\Alerts;


use app\models\AlertsToUser;
use app\models\AlertsTypes;
use app\models\TelegramToUser;
use app\models\User;

class AlertsNotificationUsersArray
{

    public static function MailArrayGenerate($type)
    {
        $mailArray=[];
        $users= User::find()->where(['email_notification_enable'=>'1'])->all();

        foreach ($users as $user) {
            $alertsToUser= AlertsToUser::find()->where(['user_id'=>$user->id])->all();
            $typeArray=[];
            foreach ($alertsToUser as $alert) {
                $typeA= AlertsTypes::findOne(['id'=>$alert->alerts_type_id]);
                $typeArray[]=$typeA['name'];
            }

            if(in_array($type,$typeArray))
            {
                $mailArray[]=$user->email;
            }


        }

        return $mailArray;
    }

    public static function TelegramArrayGenerate($type)
    {
        $telegramArray=[];
        $users= User::find()->where(['telegram_notification_enable' => '1'])->all();

        foreach ($users as $user) {
            $alertsToUser= AlertsToUser::find()->where(['user_id'=>$user->id])->all();
            $typeArray=[];
            foreach ($alertsToUser as $alert) {
                $typeA= AlertsTypes::findOne(['id'=>$alert->alerts_type_id]);
                $typeArray[]=$typeA['name'];
            }

            if(in_array($type,$typeArray))
            {
                $telToUser= TelegramToUser::findOne(['user_id'=>$user->id]);
                if(isset( $telToUser->telegram_id )){
                    $telegramArray[]=$telToUser->telegram_id;
                }

            }


        }

        return $telegramArray;
    }


}