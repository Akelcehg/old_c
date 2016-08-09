<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AllAlerts
 *
 * @author alks
 */

namespace app\components;

use \yii\base\Widget;
use yii\helpers\Html;
use Yii;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use app\models\Regions;
use yii\helpers\ArrayHelper;

class AllCountersTabFilter extends Widget {
    public $searchModel;

    public function init() {

        $form = ActiveForm::begin([ 'method' => 'GET',
                    'options' => [
                        'class' => 'smart-form',
                        'data-pjax' => 1,
                        'id' => 'UserCounters',
                    ]
        ]);
        echo $form->field($this->searchModel, 'id')->hiddenInput(['value' => 0, 'id' => 'address'])->label(false)->error(false);
        ?>
        <div class="row">
            <section class="input col col-2">
                <div class="input-group">
                    <span class="input-group-addon clickable"> <i class="fa fa-calendar"></i></span>
        <?php
        echo $form->field($this->searchModel, 'beginDate')->widget(DatePicker::classname(), [
            


            'dateFormat' => 'yyyy-MM-dd',
            'clientOptions' => [
                'nextText' => '>',
                'prevText' => '<',
            ],
            'options' => [ 'id' => 'beginDate',],
        ])->label(false)->error(false);
        ?>
                </div>
            </section>

            <section class="input col col-2">
                <div class="input-group">
                    <span class="input-group-addon clickable"> <i class="fa fa-calendar"></i></span>
        <?php


        echo $form->field($this->searchModel, 'endDate')->widget(DatePicker::classname(), [
           

            'dateFormat' => 'yyyy-MM-dd',
            'clientOptions' => [
                'nextText' => '>',
                'prevText' => '<',
            ],
            'options' => [ 'id' => 'endDate',
            ],
        ])->label(false)->error(false);
        ?>
                </div>
            </section>

            <div class="col col-2">
     <?php if(array_key_exists('admin', Yii::$app->authManager->getRolesByUser(Yii::$app->user->id))){     ?>      
                <section>
                    <label class="select">
        <?php
        echo $form->field($this->searchModel, 'region')
                ->dropDownList([Yii::t('counter','Chose region')] + ArrayHelper::map(Regions::find()->where('parent_id=0')->all(), 'id', 'name'), [
                    'value' => Yii::$app->request->get('UserCounters[region]', 0),
                    'id' => 'region'
                ])
                ->label(false)->error(false);


        ?>
                        <i></i>
                    </label>
                </section>
     <?php }?>   
            </div>

            <div class="col col-2">
                <?php if(array_key_exists('admin', Yii::$app->authManager->getRolesByUser(Yii::$app->user->id))){ ?>
                <section>
                    <label class="select">
        <?php
        echo $form->field($this->searchModel, 'region_id')
                ->dropDownList([Yii::t('counter','Chose region')] + ArrayHelper::map(Regions::find()->where('parent_id!=0')->all(), 'id', 'name'), [
                    'value' => Yii::$app->request->get('UserCounters[region_id]', 0),
                    'id' => 'city'
                ])
                ->label(false)->error(false);


        ?>
                        <i></i>
                    </label>
                </section>
                 <?php }?>
            </div>
            <div class="col col-2">
               
                <section>
              
        <?php
        echo $form->field($this->searchModel, 'exploitation')
                
                ->checkbox(['value' => Yii::$app->request->get('UserCounters[exploitation]', 0),'id' => 'exploitation'])
                ->label(false)
                ->error(false);


        ?>
                        
                    
                </section>
               
            </div>
        <?php
//echo Html::dropDownList('house','',['Выберите дом'] ,['id'=>'house']);
        ?>
            <div class="col col-2">
            <?php
            echo Html::Button(Yii::t('counter','Submit filter'), [
                'id' => 'allCounterFilterSubmit',
                'class' => 'btn btn-primary',
                'style' => 'padding: 6px 12px',
                //'onclick'=>'$("#browse-address-grid").yiiGridView("applyFilter"); ',
                //'onclick'=>'$.pjax.reload({container:"#somepjax"});',
                'data-pjax' => true

                    //
            ]);
            ?>
            </div>

            <div class="col col-2">
        <?php
        echo Html::Button(Yii::t('counter','Reset filter'), [
            'id' => 'reset',
            'class' => 'btn btn-primary',
            'style' => 'padding: 6px 12px;background-color:#458B00',
                //
        ]);
        ?>
            </div>

        </div>



        <?php
         echo AllAlerts::widget(
          []);
          ActiveForm::end(); 
    }

}
