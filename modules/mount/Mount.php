<?php

namespace app\modules\mount;

class Mount extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\mount\controllers';

    public function init()
    {
        parent::init();
        $this->layout = 'mount_layout';
        // custom initialization code goes here
    }
}
