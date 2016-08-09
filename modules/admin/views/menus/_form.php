<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>
<div style="padding-left:20px">
	<?php if(!$menu->getIsNewRecord() && $newRecord) :?>
	<?php elseif($newRecord):?>
	<h1>Add new sub-menu</h1>
	<?php else:?>
	<h1>Edit menu</h1>
	<?php endif;?>


	<?php if(Yii::$app->session->hasFlash('menus')): ?>

	<div class="flash-success">
		<?php echo Yii::$app->session->getFlash('menus'); ?>
	</div>

	<?php endif;?>

	<div class="form">
	<?php $form=ActiveForm::begin(array(
		'id'=>'menu-form',
		'enableClientValidation'=>true,
		'options'=>array(
			'validateOnSubmit'=>true,
		),
	)); ?>

		<p class="note">Fields with <span class="required">*</span> are required.</p>
		<div id="row">
			<?php echo $form->field($menu,'owner_id');?>
			<?php /*$this->widget('SelectTreeWidget',array(
				'data'			=> $menusTreeData,
				'checked'	 	=> $menu->owner_id,
				'name'			=> 'MenuItem[owner_id]',
				'attributes'	=> array(
					'prompt' => 'Please select menu',
					'disabled' => (!$menu->getIsNewRecord() && $newRecord)?true:false,
				),
			))*/?>
			
		</div>
		<div class="row">
			
			<?php echo $form->field($menu,'label')->textInput(); ?>
			
		</div>


		<div class="row buttons">
			<?php echo Html::submitButton('Submit'); ?>
		</div>

	<?php ActiveForm::end(); ?>
	<!-- form -->
	</div>
</div>
<div>
</div>
