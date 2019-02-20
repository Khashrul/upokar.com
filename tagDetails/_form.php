<?php
/* @var $this TagDetailsController */
/* @var $model TagDetails */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tag-details-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<label for="sel1">Select Parent Services:</label>
		<select name="TagDetails[parent_service_id]" id="parent_service_id" class="form-control">
			<?php
			$all_services = Generic::getAllParentServices();
			foreach($all_services as $key => $individual_service){?>
				<option value="<?=$key?>"><?=$individual_service?></option>
			<?php }
			?>
		</select>
	</div>


	<div id ="sub-service-id" class="row">
		<input type="hidden" id="sub_service_id" value="">

	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'tag_name'); ?>
		<?php echo $form->textField($model,'tag_name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'tag_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tag_price'); ?>
		<?php echo $form->textField($model,'tag_price',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'tag_price'); ?>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php
$cs = Yii::app()->getClientScript();
$baseUrl = Yii::app()->request->baseUrl;
$cs->registerScriptFile($baseUrl."/js/jquery.min.js", CClientScript::POS_HEAD);
?>
<script type="text/javascript">
	function baseUrl() {
		var href = window.location.href.split('/');
		return href[0]+'//'+href[2]+'/';
	}
	var SITE_URL=baseUrl();

	$( document ).ready(function() {
		$("#parent_service_id").change(function() {
			var parent_service_id = ( $('option:selected', this).val() );


			$.ajax({
				type:"POST",
				dataType:"json",
				url:SITE_URL + "site/LoadSubService",
				data:{parent_service_id:parent_service_id},
				success: function (data) {
					$("#sub-service-id").html(data.html);
				},
				error:function(){
					console.log('ajax error')}
			});
		});

	});

</script>