<?php
/* @var $this TagDetailsController */
/* @var $data TagDetails */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('parent_service_id')); ?>:</b>
	<?php echo CHtml::encode($data->parent_service_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sub_service_id')); ?>:</b>
	<?php echo CHtml::encode($data->sub_service_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tag_name')); ?>:</b>
	<?php echo CHtml::encode($data->tag_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tag_price')); ?>:</b>
	<?php echo CHtml::encode($data->tag_price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_date')); ?>:</b>
	<?php echo CHtml::encode($data->create_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_date')); ?>:</b>
	<?php echo CHtml::encode($data->update_date); ?>
	<br />


</div>