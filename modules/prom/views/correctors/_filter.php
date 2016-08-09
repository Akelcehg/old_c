<form method="GET" class="smart-form">
    <div id="filter" class="clientSearch dashboard">
        <div class="row">
            <div class="col col-6">
                <label class="control-label">&nbsp;</label>

                <div class="input-group col-xs-12">
                    <label class="input">
                        <input type="text" id="search_q" name="search[q]" value="<?php echo (!empty($_GET['search']) && !empty($_GET['search']['q'])) ? $_GET['search']['q'] : ''  ?>" class="form-control" placeholder="<?=Yii::t('prom','Company name')?>">
                    </label>
                    <input type="hidden"  name="type" value="<?php echo Yii::$app->request->get('type','prom')?>" >


                    <div class="input-group-btn btn-search">
                        <button type="button" id="searchFilterSubmitButton" class="filter_option filter_submit smart-btn btn btn-default"><i class="fa fa-fw fa-search"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
