<?php
namespace app\modules\prom\components;
use app\models\AlertsList;
use app\models\CorrectorToCounter;
use yii\grid\GridView;
use yii\helpers\Html;


class SummaryMomentDataButton extends \yii\base\Widget
{
    public function run() {

        $this->getView()->registerJs("$(function () {

            start();

            function getCookie(name) {
                                      var matches = document.cookie.match(new RegExp(
                                        \"(?:^|; )\" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + \"=([^;]*)\"
                                      ));
                                      return matches ? decodeURIComponent(matches[1]) : undefined;
                                    }



            function start(){    if( getCookie('forceMD')==1 ){
                checkMD()
           };}


            $('#ForcedMD').click(function () {

                        $.get(app.baseUrl+'/prom/correctors/ajaxforcemd', {

                        }, function (htmlData) {
                            start();
                            $('#ForcedMDContainer').replaceWith(htmlData);

                        });

            })


            function checkMD() {



                            $.get(app.baseUrl+'/prom/correctors/ajaxcheckmd', {

                            }, function (htmlData) {

                                $('#ForcedMDContainer').replaceWith(htmlData);

                            });
                            if( getCookie('forceMD')!=1 ){ location.reload();}
                            else{setTimeout(checkMD,5000);}




             }



         });",2);

        echo "<p id='ForcedMDContainer' style=\"font-family: 'Open Sans';font-size: 18px;color: white;margin-bottom:0px\">";
        echo Html::tag('span','Обновить данные',['id'=>"ForcedMD", 'class' => 'prom-buttons', "style" => "cursor:pointer"]);
        echo "</p>";

    }

}