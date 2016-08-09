<div class="clientSearch">
    <?php
    echo CHtml::beginForm(Yii::app()->request->baseUrl.'/admin/users/closedUsers', 'GET', array('class'=>'form-horizontal styled-form'));
    ?>

    <div class="row">
        <div class="search_block col-sm-3 no-margin" >
            <?php echo CHtml::textField('search', Yii::app()->request->getQuery('search'),array('class'=>'form-control'));?>
        </div>
        <div class="search_block no-margin col-sm-1" >
            <input type="submit" value="Search" name="search_button" class="btn btn-primary"/>
        </div>
        <div class="col-sm-4 pull-right no-margin">
            <?php
            echo $this->createWidget('application.extensions.managePageSize.ManagePageSize', array(
                'gridId' => 'browse-users-grid',
                'dropDownListId' => 'pageSize',
                'class'=>'form-control pull-right users-page-size',
                'labelClass'=>'control-label users-page-size-label col-sm-8',
            ))->getList();
            ?>
        </div>
    </div>

    <?php echo CHtml::endForm(); ?>
    <div style="float:right;">
        <span id="count_users" class="count_users"></span>
    </div>
    <div class="clear"></div>
</div>

<div id="closed-user-grid">
    <?php
    $this->widget('application.components.GridView', array(
        'id' => 'browse-users-grid',
        'dataProvider' => $dataProvider,
        //'cssFile' => Yii::app()->request->baseUrl . '/css/qualification/user/grid.css',
        'pager' => array(
            'header' => '',
            'htmlOptions' => array('class'=>'pagination'),
            'selectedPageCssClass' => 'active',
            'firstPageCssClass' => 'hide',
            'lastPageCssClass' => 'hide',
            'prevPageLabel' => 'Previous',
            'nextPageLabel' => 'Next',
        ),
        'summaryText' => false,
        'pagerCssClass' => 'dataTables_paginate paging_bootstrap_full',
        'itemsCssClass' => 'grid-items',
        'gridHtmlOptions' => array(
            'cellspacing' => 0, 
            'cellpadding' => 0, 
            'class' => 'grid-items table table-striped table-bordered table-hover dataTable'
        ),
        'template' => "<div id='browse-users-grid_container' class='dt-wrapper closedUsers'>{items}<div class='row'><div class='col-sm-sm-6 text-right pull-right'>{pager}</div></div></div>",
        'selectableRows'=>0,
        'columns' => array(
            array (
                'name' => 'user_id',
                'headerHtmlOptions' => array('class' => 'firstColumn'),
                'htmlOptions' => array('class' => 'firstColumn'),
            ),
            array(
                'name' => 'user.first_name',
                'header' => 'Name',
                'value' => '$data->user->first_name." ".$data->user->last_name',
                'htmlOptions' => array('class' => 'breakword'),
            ),
            array(
                'name' => 'user.email',
                'header' => 'Email',
            ),
            'reason',
            array(
                'class'=>'TbButtonColumn',
                'header' => 'Actions',
                'template'=>'{recover}',
                'buttons' => array(
                    'recover' => array(
                        'label' => 'Reactivate Account',
    //                    'imageUrl' => Yii::app()->request->baseUrl . '/images/activate.png',
                        'icon' => 'fa fa-plus-circle',
                        'url' => 'Yii::app()->createUrl("admin/users/reactivateUser", array("id" => $data->user_id))',
                        'visible'=>'!Yii::app()->authManager->isAssigned("moderator", Yii::app()->user->getId())',
                        'options' => array(
                            'class' => 'reactive-user',
                        )
                    ),
                )
            )
        )
    ));

    ?>
</div>