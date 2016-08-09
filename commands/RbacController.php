<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        //Clean previous roles
        $auth->removeAll();
        
        //Permissions
        $viewOnly = $auth->createPermission('viewOnly');
        $viewOnly->description = 'Can current user group only watch';
        $auth->add($viewOnly);

        $mountCounter = $auth->createPermission('mountCounter');
        $mountCounter->description = 'Can current user group mount counter. I.e. access mount module';
        $auth->add($mountCounter);

        $watchGasCounter = $auth->createPermission('watchGasCounter');
        $watchGasCounter->description = 'Can current user group watch counters with type "gas"';
        $auth->add($watchGasCounter);

        $watchWaterCounter = $auth->createPermission('watchWaterCounter');
        $watchWaterCounter->description = 'Can current user group watch counters with type "water"';
        $auth->add($watchWaterCounter);

        $objectOwner = $auth->createPermission('objectOwner');
        $objectOwner->description = 'Can watch object where owner';
        $auth->add($objectOwner);

        $watchRegion = $auth->createPermission('watchRegion');
        $watchRegion->description = 'Can watch object of own region';
        $auth->add($watchRegion);

        //Groups
        $adjuster = $auth->createRole('adjuster');
        $auth->add($adjuster);
        $auth->addChild($adjuster, $mountCounter);

        $regionWatcher = $auth->createRole('regionWatcher');
        $auth->add($regionWatcher);
        $auth->addChild($regionWatcher, $watchRegion);

        $gasWatcher = $auth->createRole('gasWatcher');
        $auth->add($gasWatcher);
        $auth->addChild($gasWatcher, $watchGasCounter);

        $waterWatcher = $auth->createRole('waterWatcher');
        $auth->add($waterWatcher);
        $auth->addChild($waterWatcher, $watchWaterCounter);

        $viewer = $auth->createRole('viewer');
        $auth->add($viewer);
        $auth->addChild($viewer, $viewOnly);

        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $watchWaterCounter);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        //admin can watch gas counters
//        $auth->addChild($admin, $gasWatcher);
//        //admin can watch water counters
//        $auth->addChild($admin, $waterWatcher);
//        //admin can access counter mount module
//        $auth->addChild($admin, $adjuster);
        //admin can own region object. Just for sure if needed
//        $auth->addChild($admin, $user);
        //watch own region also
//        $auth->addChild($admin, $watchRegion);
    }
}