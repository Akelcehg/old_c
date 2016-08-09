<?php

namespace app\components;

use \yii\base\Component;
use \app\models\Counter;
use \app\models\User;
use Yii;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AjaxLinkPager
 *
 * @author alks
 */
class CounterQuery extends Component {

    public static function counterQueryByRole() {
        $geoId = Yii::$app->request->get('geo_location_id', 0);
        
        $whereInstalled = Yii::$app->request->get('where_installed', 0);

        $counterList = Counter::find();

        if (CounterQuery::isRole('admin')) {
            //добавляем условие   для  role
            $counterList->joinWith('user')->joinWith('address');
            if ($geoId) {
                $counterList->andWhere('counters.geo_location_id = :geo_location_id', [':geo_location_id' => $geoId]);
            }
            
            if ($whereInstalled) {
                $counterList->andWhere('where_installed = :whereInstalled ', [':whereInstalled ' => $whereInstalled]);
            }
        }
        
        if (CounterQuery::isRole('admin_gas')) {
            //добавляем условие   для  role
            $counterList->joinWith('user')->joinWith('address');
            if ($geoId) {
                $counterList->andWhere('counters.geo_location_id = :geo_location_id', [':geo_location_id' => $geoId]);
            }
            
            if ($whereInstalled) {
                $counterList->andWhere('where_installed = :whereInstalled ', [':whereInstalled ' => $whereInstalled]);
            }
        }

        if (CounterQuery::isRole('admin_region')) {
            //добавляем условие   для  role
            //$counterList->where('user_counters.flat = :flat', [':flat' => '0']);
            
            
            if ($whereInstalled) {
                $counterList->andWhere('where_installed = :whereInstalled ', [':whereInstalled ' => $whereInstalled]);
            }
            
            $user = User::find()->where(['id' => Yii::$app->user->id])->one();
            $counterList->andWhere('address.region_id = :geo_location_id', [':geo_location_id' => $user->geo_location_id])->joinWith('user')->joinWith('address');
        }

        if (CounterQuery::isRole('user')) {
            //добавляем условие   для  role



            $counterList->where(['user_id' => Yii::$app->user->id]);

           
            if ($whereInstalled) {
                $counterList->andWhere('where_installed = :whereInstalled ', [':whereInstalled ' => $whereInstalled]);
            }
        }

        return $counterList;
    }

    public static function counterIdQueryByRole($id) {
        
        $counterList = UserCounters::find()->select('serial_number');
        
        $type = Yii::$app->request->get('type', 'gas');
        $whereInstalled = Yii::$app->request->get('where_installed', 0);
        $counterList->where('geo_location_id = :geo_location_id', [':geo_location_id' => $id]);
        
        if (CounterQuery::isRole('admin')) {

             if ($type) {
                $counterList->andWhere('type = :type', [':type' => $type]); 
            }
            if ($whereInstalled) {
                $counterList->andWhere('where_installed = :whereInstalled ', [':whereInstalled ' => $whereInstalled]);
            }
        }

        if (CounterQuery::isRole('user')) {
            //добавляем условие   для  role
            $counterList->where(['user_id' => Yii::$app->user->id]);
        }

        return $counterList;
    }

    public static function counterQueryByRoleAndAddress($counterList) {
        
        
        $whereInstalled = Yii::$app->request->get('where_installed', 0);
        
        $type = Yii::$app->request->get('type', null);
        $counterList->joinWith('counters',true)->distinct();
        

        if (CounterQuery::isRole('admin')) {
            //добавляем условие   для  role
            
             $counterList->filterWhere(['counters.type'=>$type]);

            if ($whereInstalled) {
                $counterList->andWhere('counters.where_installed = :whereInstalled ', [':whereInstalled ' => $whereInstalled]);
            }
            
        }
        
         if (CounterQuery::isRole('admin_gas')) {
            //добавляем условие   для  role
            
  

            if ($whereInstalled) {
                $counterList->andWhere('counters.where_installed = :whereInstalled ', [':whereInstalled ' => $whereInstalled]);
            }
            
        }

        if (CounterQuery::isRole('admin_region')) {
            
            $user = User::find()->where(['id' => Yii::$app->user->id])->one();
            $counterList->filterWhere(['counters.type'=>$type]);
            $counterList->andWhere('address.region_id = :region_id', [':region_id' => $user->geo_location_id]);
            
        }

        if (CounterQuery::isRole('user')) {
            
            //добавляем условие   для  role

            $counterList->where(['counters.user_id' => Yii::$app->user->id]);
            
        }

        return $counterList;
    }

    public static function isRole($role) {
        return array_key_exists($role, Yii::$app->authManager->getRolesByUser(Yii::$app->user->id));
    }

    
    
    public static function counterQueryByRoleAndAddressGVF($counterList) {
        
        $type = Yii::$app->request->get('type', 'gas');
        $whereInstalled = Yii::$app->request->get('where_installed', 0);

        $counterList->joinWith('counters',true)->distinct();

        if (CounterQuery::isRole('admin')) {
            //добавляем условие   для  role
            
            if ($type) {
                $counterList->andWhere('user_counters.type = :type', [':type' => $type]);
            }

            if ($whereInstalled) {
                $counterList->andWhere('user_counters.where_installed = :whereInstalled ', [':whereInstalled ' => $whereInstalled]);
            }
            
        }
        
        if (CounterQuery::isRole('admin_gas')) {
            //добавляем условие   для  role
            
            if ($type) {
                $counterList->andWhere('user_counters.type = :type', [':type' => $type]);
            }

            if ($whereInstalled) {
                $counterList->andWhere('user_counters.where_installed = :whereInstalled ', [':whereInstalled ' => $whereInstalled]);
            }
            
        }

        if (CounterQuery::isRole('admin_region')) {
            
            $user = User::find()->where(['id' => Yii::$app->user->id])->one();
            $counterList->andWhere('counter_address.region_id = :geo_location_id', [':geo_location_id' => $user->geo_location_id]);
            
        }

        if (CounterQuery::isRole('user')) {
            
            //добавляем условие   для  role

            $counterList->where(['user_counters.user_id' => Yii::$app->user->id]);
            
        }

        return $counterList;
    }
    
    
    
}
