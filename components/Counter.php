<?php

/**
 * Created by PhpStorm.
 * User: akel
 * Date: 7/23/15
 * Time: 12:36 PM
 */

namespace app\components;

use app\models\Indication;

use yii\base\Component;
use Yii;

class Counter extends Component
{

    public $userCounter;
    private $counterRecord;


    public function __construct(\app\models\Counter $userCounter, $counterRecord, $config = [])
    {
        parent::__construct($config);

        $this->userCounter = $userCounter;
        $this->counterRecord = $counterRecord;
    }


    public function saveIndications()
    {
        $this->userCounter->setAttributes($this->counterRecord, false);
        $this->userCounter->last_indications = $this->userCounter->initial_indications;
        $this->userCounter->save();

        if (Indication::find()->where(['counter_id' => $this->userCounter->id])->count() < 5) {

            $indication = new Indication();
            $indication->counter_id = $this->userCounter->id;

            $indication->indications = $this->userCounter->initial_indications;

            $indication->created_at = date ('Y-m-d h:i:s');

            return $indication->save();
        }

        return true;

    }

    public function saveWithImage($hasImage, $image)
    {

        if ($hasImage) {

            $resizedFile = $image;

//            $path = 'img/gas/' . $this->userCounter->id;
            $path = rtrim(Yii::$app->params['countersPhotoPath'], DIRECTORY_SEPARATOR). DIRECTORY_SEPARATOR . $this->userCounter->id . DIRECTORY_SEPARATOR;

            //if Directory Not Exists
//            if (!file_exists($path))
//                mkdir($path);

            $this->saveCounterImage($resizedFile, $path);

        }

        $this->userCounter->setAttributes($this->counterRecord, false);

        $events = new Events();
        $events->oldAttributes = $this->userCounter->getOldAttributes();

        //if(empty($this->userCounter->last_indications)){
        //$this->userCounter->last_indications = $this->userCounter->initial_indications;}
       if($result=$this->userCounter->save()){

           $events->newAttributes = $this->userCounter->getAttributes();
           $events->model = $this->userCounter;
           $events->type = 'edit';
           $events->AddEvent();
       }



        if (Indication::find()->where(['counter_id' => $this->userCounter->id])->count() < 1 and !isset($this->counterRecord['mount'])) {

            $indication = new Indication();
            $indication->counter_id = $this->userCounter->id;

            
            $indication->indications = $this->userCounter->initial_indications;
            
            $indication->created_at = date ('Y-m-d h:i:s');
            
            $indication->save();
        }

        return $result;
    }

    private function saveCounterImage($image, $path)
    {
        if (!file_exists($path)) {
            mkdir($path);
        }

        file_put_contents($path . $this->userCounter->id . '.png', fopen($image, 'r'));

        return true;
    }

}
