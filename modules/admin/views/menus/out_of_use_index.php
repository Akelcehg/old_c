<div style="padding:20px;">
<?php if(Yii::app()->user->hasFlash('menus')): ?>
<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('menus'); ?>
</div>
<?php endif;?>
<?php 
        
?>
<h2>Menus (<?php ?>)</h2>
<?php if(count($tree) == 0) echo "No elements found"?>
<?php $this->widget('CTreeView', array(
									'data' => $tree,
								)
					);?>
</div>