<?php
/* @var $this ShopCategoryController */
/* @var $data ShopCategory */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('category_name')); ?>:</b>
	<?php echo CHtml::encode($data->category_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('category_image')); ?>:</b>
	<?php echo CHtml::encode($data->category_image); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sub_category_name')); ?>:</b>
	<?php echo CHtml::encode($data->sub_category_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sub_category_image')); ?>:</b>
	<?php echo CHtml::encode($data->sub_category_image); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_date')); ?>:</b>
	<?php echo CHtml::encode($data->create_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_date')); ?>:</b>
	<?php echo CHtml::encode($data->update_date); ?>
	<br />


</div>