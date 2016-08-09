<?php 
use yii\helpers\Html;
use app\components\managePageSize\ManagePageSize;
use yii\grid\GridView;
use yii\grid\ActionColumn;


    if(Yii::$app->session->getFlash('passchanged')){ ?>
    <div class="alert alert-success fade in">
        <button class="close" data-dismiss="alert"> × </button>
        <i class="fa-fw fa fa-check"></i>
        <?=Yii::$app->session->getFlash('passchanged');?>
    </div>
<?php } ?>
<div class="clientSearch">
    <?php
    $searchedValue = (Yii::$app->request->get('search')) ? Yii::$app->request->get('search') : '';
    $selectedNativeLanguage = (Yii::$app->request->get('nativeLanguageFilter')) ? Yii::$app->request->get('nativeLanguageFilter') : '0';
    $selectedSpokenLanguage = (Yii::$app->request->get('spokenLanguageFilter')) ? Yii::$app->request->get('spokenLanguageFilter') : '0';
   
    //$languages = Language::find()->where(['order' => 'name'])->all();
    //$closePerm = !Yii::$app->authManager->checkAccess(Yii::$app->user->getId(),'moderator');
    ?>
   
    <div style="float:right;">
        <span id="count_users" class="count_users"></span>
    </div>
    <div class="clear"></div>
</div>

<?php

$this->registerJs('setOptions', "
    setOption('closeUserUrl', '".Yii::$app->getUrlManager()->createUrl('admin/users/deleteUser')."');
    setOption('exportUsersUrl', '".Yii::$app->getUrlManager()->createUrl('admin/users/exportUsers')."');
    setOption('gridId', 'browse-users-grid');
    ");

//echo $this->render('_closeUserPopUp');

echo GridView::widget(
        [
    'id' => 'browse-users-grid',
    'dataProvider' => $dataProvider,
    'options' =>[
        'cellspacing' => 0, 
        'cellpadding' => 0, 
        'class' => 'grid-items table table-striped table-bordered table-hover dataTable'
    ],
    'pager' => [
        'options' =>[
        'pagerCssClass' => 'dataTables_paginate paging_bootstrap_full',
        'itemsCssClass' => 'grid-items',
        'class'=>'pagination',
        'selectedPageCssClass' => 'active',
        'firstPageCssClass' => 'hide',
        'lastPageCssClass' => 'hide',
        'prevPageLabel' => 'Previous',
        'nextPageLabel' => 'Next',
        'template' => "{summary}<div id='browse-users-grid_container' class='dt-wrapper'>{items}<div class='row'><div class='col-sm-sm-6 text-right pull-right'>{pager}</div></div></div>",
        'selectableRows'=>0,
           ],
        
        
    ],
    'columns' => 
        [
            'id',
            'email',
            'first_name',
            'last_name',
            'nick_name',
            'role',
            ['attribute'=>'status','value'=>function($model){return $model->getStatusName();}],
            [
            'class' => ActionColumn::className(),
            'header' => '-',
            'options'=>
                        [
                          'class'=>'button-column',
                          'width'=>'60px',
                        ]  , 
            'template'=>'{login}&#160;{edit}',
            'buttons' => [         
                'edit' =>  function($url,$model){
                   $url=Yii::$app->urlManager->createUrl(["admin/users/edituser",'id' =>$model->id,"&backUrl=admin/users"]);
                   $label='Ereate Account';
                    return \yii\helpers\Html::a( '<i class="fa fa-edit"></i>', $url,['title' => Yii::t('yii', $label), 'data-pjax' => '0']);
                },
                'delete' =>  function($url,$model){
                   $url=Yii::$app->urlManager->createUrl(["admin/users/deleteuser",'id' =>$model->id,"&backUrl=admin/users"]);
                   $label='Delete Account';
                    return \yii\helpers\Html::a( '<i class="fa fa-times"></i>', $url,['title' => Yii::t('yii', $label), 'data-pjax' => '0']);
                }
            ]
        ]
       
    ]
            ]

        );
?>

<script type="text/javascript">
$("#browse-users-grid_container table tr").live("dblclick",function() {
    id = $(this).find('td:first-child').text();
    url = "<?php echo Yii::$app->getUrlManager()->createUrl('admin/users/viewUser', array('backTo' => 'approvedUsers','id' => '')); ?>" + id;
    if(parseInt(id)){
        location.href = url;
    }
});
</script>
<?php
/*
 * 
 * '@app/www/css/qualification/user/grid.css'
 *
 array(
            'header' => 'User ID',
            //'name' => 'id',
            //'headerHtmlOptions' => array('class' => 'firstColumn'),
            //'htmlOptions' => array('class' => 'firstColumn'),
        ),
        array(
            'header' => 'Name',
            //'name' => 'fullname',
            'value' => '$data["first_name"] . "  " . $data["last_name"]',
            //'htmlOptions' => array('class' => 'breakword'),
        ),

        array(
            'header' => 'Native Language',
            //'name' => 'motherLang1.name',
            'value' => '((!is_null($data["mother_language"])) ? $data["mother_language"] : "N/A")',
        ),

        array(
            'header' => 'Spoken Languages',
            //'name' => 'spoken_languages',
            'value' => '(empty($data["spoken_languages"]) ? "N/A" : $data["spoken_languages"])',
            //'htmlOptions' => array('class' => 'breakword'),
        ),
        array(
            'header' => 'Email',
           // 'name' => 'email',
            'value' => '"<div>".$data["email"]."</div>"',
            //'type' => 'html',
            //'htmlOptions' => array('class' => 'email breakword')
        ),
        array (
            'header' => 'Country',
           // 'name' => 'country',
            'value' => '$data["country"]',
        ),

        'role:text:Role',

        array(
            'header' => 'Last login',
            //'name' => 'last_login',
            'value' => '"<div>". DateTimeHelper::render($data["last_login"]) ."</div>"',
            //'htmlOptions' => array('class' => 'last_date'),
            //'type' => 'html'
        ),
        


        array(
            //'class' => 'TbButtonColumn',
            'header' => 'Actions',
            //'template'=>'{login}&#160;{closeUser}',
           /* 'buttons' => array(
                'login' => array(
                    'label' => 'Browse Account',
                    'icon' => 'fa fa-user',
                    'url'=>'Yii::$app->createUrl("admin/users/logInAsUser")."?id=".$data["id"]."&backUrl=admin/users".((isset($_GET["page"])) ? "?page=".$_GET["page"] : "")'
                ),
                'closeUser' => array(
                    'label' => 'Close Account',
                    'icon' => 'fa fa-times',
                    'click' => 'js:function(){openCloseUserPopUp($(this).parent().parent().children(":first-child").text()); return false;}',
                    'visible'=> $closePerm ? 'true' : 'false'
                ),
            )
        )

*/?>