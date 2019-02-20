<?php
/* @var $this SubServiceController */
/* @var $data SubService */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('parent_service_id')); ?>:</b>
	<?php echo CHtml::encode($data->parent_service_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('service_name')); ?>:</b>
	<?php echo CHtml::encode($data->service_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('service_image')); ?>:</b>
	<?php echo CHtml::encode($data->service_image); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('initial_price')); ?>:</b>
	<?php echo CHtml::encode($data->initial_price); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('service_description')); ?>:</b>
	<?php echo CHtml::encode($data->service_description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('service_condition')); ?>:</b>
	<?php echo CHtml::encode($data->service_condition); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('replace_policy')); ?>:</b>
	<?php echo CHtml::encode($data->replace_policy); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('review')); ?>:</b>
	<?php echo CHtml::encode($data->review); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_date')); ?>:</b>
	<?php echo CHtml::encode($data->create_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('update_date')); ?>:</b>
	<?php echo CHtml::encode($data->update_date); ?>
	<br />

	*/ ?>

</div>