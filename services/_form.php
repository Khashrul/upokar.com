<?php
/* @var $this ServicesController */
/* @var $model Services */
/* @var $form CActiveForm */
?>

<div class="form">

	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'services-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// There is a call to performAjaxValidation() commented in generated controller code.
		// See class documentation of CActiveForm for details on this.
		'enableAjaxValidation'=>false,
		'htmlOptions'=>array(
			'enctype' => 'multipart/form-data',
		),
	)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'service_name'); ?>
		<?php echo $form->textField($model,'service_name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'service_name'); ?>
	</div>

	<div class="row">
		<?php
		if(!$model->isNewRecord){
			echo Generic::showImage("service_image", $model->service_image);
		}
		?>
		<?php echo $form->labelEx($model,'service_image'); ?>
		<?php echo $form->fileField($model,'service_image'); ?>
		<?php echo $form->error($model,'service_image'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'service_description'); ?>
		<?php echo $form->textArea($model,'service_description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'service_description'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->