<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

use app\models\User;
use Yii;

use yii\base\Component;

class RightsComponent extends Component
{
    public function updateSearchModelCounter($searchModel)
    {

        $currentUser = new User();
        if (!User::is('admin')) {

            if (User::is('regionWatcher')) $searchModel->region_id = $currentUser->getCurrentUserRegionId(Yii::$app->user->id);

            if (User::is('gasWatcher') || User::is('waterWatcher')) {
                if (User::is('gasWatcher')) $searchModel->type = 'gas';
                if (User::is('waterWatcher')) $searchModel->type = 'water';
            }

        } else {

            $searchModel->type = Yii::$app->request->post('type', null);

            if (User::is('regionWatcher')) $searchModel->region_id = $currentUser->getCurrentUserRegionId(Yii::$app->user->id);

            if (User::is('gasWatcher') || User::is('waterWatcher')) {
                if (User::is('gasWatcher')) $searchModel->type = 'gas';
                if (User::is('waterWatcher')) $searchModel->type = 'water';
            }

        }

        return $searchModel;

    }


    public function updateSearchModelEvent($searchModel)
    {


        $currentUser = new User();
        if (!User::is('admin')) {

            $searchModel->counter_type = null;

            if (User::is('regionWatcher')) $searchModel->region_id = $currentUser->getCurrentUserRegionId(Yii::$app->user->id);

            if (User::is('gasWatcher') || User::is('waterWatcher')) {
                if (User::is('gasWatcher')) $searchModel->counter_type = 'gas';
                if (User::is('waterWatcher')) $searchModel->counter_type = 'water';
            }

        } else {

            //$searchModel->type = Yii::$app->request->post('type', null);

            if (User::is('regionWatcher')) $searchModel->region_id = $currentUser->getCurrentUserRegionId(Yii::$app->user->id);

            if (User::is('gasWatcher') || User::is('waterWatcher')) {
                if (User::is('gasWatcher')) $searchModel->counter_type = 'gas';
                if (User::is('waterWatcher')) $searchModel->counter_type = 'water';
            }

        }

        return $searchModel;

    }

    public function updateSearchModel($searchModel)
    {

        $currentUser = new User();
        if (!User::is('admin')) {

            if (User::is('regionWatcher')) $searchModel->address_id = $currentUser->getCurrentUserRegionId(Yii::$app->user->id);

            if (User::is('gasWatcher') || User::is('waterWatcher')) {
                if (User::is('gasWatcher')) $searchModel->type = 'gas';
                if (User::is('waterWatcher')) $searchModel->type = 'water';
            }

        } else {

            $searchModel->type = Yii::$app->request->post('type', null);

            if (User::is('regionWatcher')) $searchModel->address_id = $currentUser->getCurrentUserRegionId(Yii::$app->user->id);

            if (User::is('gasWatcher') || User::is('waterWatcher')) {
                if (User::is('gasWatcher')) $searchModel->type = 'gas';
                if (User::is('waterWatcher')) $searchModel->type = 'water';
            }

        }

        return $searchModel;

    }



}