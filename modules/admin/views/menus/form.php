
<?php echo CHtml::link('Back to menus list',array('/admin/menus'))?>
<div style="padding-left:20px">
	<?php if(!$menu->getIsNewRecord() && $newRecord) :?>
	<h1>Add translate</h1>
	<?php elseif($newRecord):?>
	<h1>Add Category</h1>
	<?php else:?>
	<h1>Edit category</h1>
	<?php endif;?>


	<?php if(Yii::app()->user->hasFlash('menus')): ?>

	<div class="flash-success">
		<?php echo Yii::app()->user->getFlash('menus'); ?>
	</div>

	<?php endif;?>

	<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'menu-form',
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
	)); ?>

		<p class="note">Fields with <span class="required">*</span> are required.</p>
	<?php //if($category->getIsNewRecord() || !$newRecord) {?>
		<div id="row">
			<?php echo $form->labelEx($menu,'owner_id')?>
			<?php echo CHtml::radioButton('MenuItem[owner_id]',($menu->owner_id == 0)?"checked":"",array('value' => 0))?>!No Menu!
			<?php $this->widget('CTreeView', array(
									'data' => $menusTreeData,
								)
					);?>
			<?php echo $form->error($menu,'owner_id'); ?>
		</div>
	<?php //}?>
		<div class="row">
			<?php echo $form->labelEx($menu,'label'); ?>
			<?php echo $form->textField($menu,'label'); ?>
			<?php echo $form->error($menu,'label'); ?>
		</div>

		<div class="row">
			<?php echo $form->labelEx($menu,'text'); ?>
			<?php
	            $this->widget('ext.editMe.ExtEditMe', array(
	                'model' => $menu,
	                'attribute' => 'text',
	                'htmlOptions' => array('option' => 'value'),
	            ));
			?>
			<?php echo $form->error($menu,'text'); ?>
		</div>

		<div class="row buttons">
			<?php echo CHtml::submitButton('Submit'); ?>
	<?php if(!$category->getIsNewRecord() && !$newRecord):?>

		<input type="hidden" name="menu_id" value=<?php echo $menu->id?>>
		<?php echo CHtml::submitButton('Delete Menu',array('name' => 'delete_menu', 'class' => 'delete_confirm')); ?>
	<?php endif;?>
		</div>

	<?php $this->endWidget(); ?>


	<!-- form -->
	</div>
</div>
