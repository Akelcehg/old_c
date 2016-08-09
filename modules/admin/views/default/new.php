<?php
/**
 * Created by PhpStorm.
 * User: alexey
 * Date: 29.02.16
 * Time: 12:24
 */
use yii\helpers\Html;
use yii\jui\AutoComplete;



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
          window.location=app.baseUrl + \'/admin/default/new2/?\'+"id="+ui.item.counter_id;
          }else{
          window.location=app.baseUrl + \'/prom/correctors/view/?\'+"id="+ui.item.counter_id;
          }
}

});
;', $position =3);

?>


<div style="text-align: center; width: 100%;height: 100%">



    <div class="smart-form" style=" width: 50%;margin: auto; padding-top: 10% " >
        <fieldset>
            <section>
                <label class="input">

                    <?=AutoComplete::widget(['id'=>'search','name' => 'search_string','clientOptions' => [
                        'source' => "autocomplete"


                    ],]);?>
                   <!-- <input id="search" type="text" list="list" style="float: left;width:90% "> <?php echo Html::Button('Поиск', [
                        'id' => 'filterSubmit',
                        'class' => 'btn btn-primary',
                        'style' => 'padding: 6px 12px;margin:0px',
                        'data-pjax' => false]); ?>
                    <datalist id="list">
                    </datalist>-->
                </label>
            </section>
        </fieldset>
    </div>


</div>






