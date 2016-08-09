<?php
use app\models\User;
use app\models\MenuItem;
use app\components\ConquerMenu;



   Yii::$app->user->getIdentity(true);
                
    $user_id = Yii::$app->user->identity->id;
   // $user = User::model()->findByPk($user_id);
    $user = User::find()->where(['id'=>Yii::$app->user->identity->id])->one();
    //$messageDisplayStyle = ($user->countNewMessages > 0) ? "block" : "none";
    ?>
<aside id="left-panel" class="menu-on-down">

    <!-- User info -->
    <div class="login-info">
		<span> <!-- User image size is adjusted inside CSS, it should stay as it -->

			<a href="javascript:void(0);" id="show-shortcut">
				<span>
                                        
					<?php echo $user->first_name.' '.$user->last_name ?>
				</span>
            </a>

		</span>
    </div>

    <nav >
        <?php

        //print_r( Yii::$app->params['menu']);
        echo ConquerMenu::widget(array(

            'linkLabelWrapper'=>'span',
            'linkLabelWrapperHtmlOptions'=>array('class'=>'menu-item-parent'),
        ));?>

    </nav>

    <span class="minifyme"> <i class="fa fa-arrow-circle-left hit"></i> </span>

</aside>
<!-- END NAVIGATION -->




