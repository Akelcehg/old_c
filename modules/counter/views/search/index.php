<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 29.02.16
 * Time: 12:24
 */
use yii\helpers\Html;
use yii\jui\AutoComplete;
use \app\modules\counter\components\SearchResultView;



$this->registerJs('

$( "#search" ).autocomplete({
  response: function( event, ui ) {

    for (var k=0;k<ui.content.length;k++){
        ui.content[k].label=ui.content[k].search_string
        ui.content[k].value=ui.content[k].search_string
        }
    },
select: function( event, ui ) {
    if(ui.item.type=="counter"){
          window.location=app.baseUrl + \'/counter/counter/view/?\'+"id="+ui.item.counter_id;
          }else{
          window.location=app.baseUrl + \'/prom/correctors/view/?\'+"id="+ui.item.counter_id;
          }
}

});
;', $position =3);

?>


<div style="text-align: center; width: 100%;height: 100%">

    <div id="logo-groupBigImage">

        <!-- PLACE YOUR LOGO HERE -->
        <span id="logoBigImage">
            <img src="<?php echo \Yii::$app->request->baseUrl ?>/images/logo_1_11.png"/>

            <!-- <a href="tel:<?php echo \Yii::$app->params['HeaderTelephone'] ?>"style="color:#ffffff;font-size: 20px;margin-left: 50px"><?php echo \Yii::$app->params['HeaderTelephone'] ?></a>
            -->
        </span>

        <!-- END LOGO PLACEHOLDER -->

    </div>
    <form method="get">
    <div class="smart-form" style=" width: 50%;margin: auto; padding-top: 50px " >
        <fieldset>
            <section>
                <label class="input">
                    <?=AutoComplete::widget(['id'=>'search','name' => 'search_string','options'=>[
                        'style'=>"width:85%;float:left",
                        'placeholder' => Yii::t('counter','Search query'),
                    ],'clientOptions' => ['source' => "search/autocomplete"],]);?>
                  <?php echo Html::submitButton(Yii::t('counter','Search'), [
                        'id' => 'filterSubmit',
                        'class' => 'btn btn-primary',
                        'style' => 'padding: 6px 12px;margin:0px;max-width:50px%',
                        'data-pjax' => false]); ?>

                </label>
            </section>
        </fieldset>
    </div>
    </form>
    <?=SearchResultView::widget();?>
</div>

<div class="smart-form" style="position: absolute;font-size:15px;text-align:center;width: 100%;top:100%;margin:auto" >
    <p>
        <i class="fa fa-phone txt-color-teal"></i>
        +38 (048) 770-24-87
    </p>
    <p>
        &copy; ООО "АСЕР"
    </p>
</div>






