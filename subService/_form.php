<?php
/* @var $this SubServiceController */
/* @var $model SubService */
/* @var $form CActiveForm */
?>

<div class="form">
	<script language="javascript" type="text/javascript" src="<?php Yii::app()->getBaseUrl(true)?>/js/tinymce/tinymce.js"></script>
	<script language="javascript" type="text/javascript">
		tinyMCE.init({
			theme : "modern",
			mode: "exact",
			elements : "service_description,service_condition,replace_policy",
			theme_advanced_toolbar_location : "top",
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,"+ "justifyleft,justifycenter,justifyright,justifyfull,formatselect,"+ "bullist,numlist,outdent,indent",
			theme_advanced_buttons2 : "link,unlink,anchor,image,separator,"+"undo,redo,cleanup,code,separator,sub,sup,charmap",
			theme_advanced_buttons3 : "",
			height:"300px",
			width:"auto"
		});
	</script>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sub-service-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'enctype' => 'multipart/form-data',
	),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<div class="form-group">
			<label for="sel1">Select Parent Services:</label>
			<select name="SubService[parent_service_id]" id="parent_service_id" class="form-control">
				<?php
				$all_services = Generic::getAllParentServices();
				foreach($all_services as $key => $individual_service){?>
					<option value="<?=$key?>"><?=$individual_service?></option>
				<?php }
				?>
			</select>
		</div>
	</div>

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
		<?php echo $form->labelEx($model,'initial_price'); ?>
		<?php echo $form->textField($model,'initial_price',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'initial_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'service_description'); ?>
		<?php echo $form->textArea($model,'service_description',array('rows'=>6, 'cols'=>50,'id'=>'service_description')); ?>
		<?php echo $form->error($model,'service_description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'service_condition'); ?>
		<?php echo $form->textArea($model,'service_condition',array('rows'=>6, 'cols'=>50,'id'=>'service_condition')); ?>
		<?php echo $form->error($model,'service_condition'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'replace_policy'); ?>
		<?php echo $form->textArea($model,'replace_policy',array('rows'=>6, 'cols'=>50,'id'=>'replace_policy')); ?>
		<?php echo $form->error($model,'replace_policy'); ?>
	</div>





	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->