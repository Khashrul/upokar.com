<?php
/* @var $this ShopCategoryController */
/* @var $model ShopCategory */
/* @var $form CActiveForm */
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
	$( function() {
		$( "#datepicker" ).datepicker();
	} );
</script>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'shop-category-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>


	<div class="row">
		<div class="form-group">
			<label for="sel1">Select Shop Categories:</label>
			<select name="ShopCategory[category_name]" id="shop_category" class="form-control">
				<option value=" ">Select Shop Category</option>
				<?php
				$all_services = Generic::getAllProductsCategory();
				foreach($all_services as $key => $individual_service){?>
					<option value="<?=$individual_service?>"><?=$individual_service?></option>
				<?php }
				?>
			</select>
		</div>
	</div>

<!--
	<div class="row form-group add-image">
		<label class="col-sm-3 label-title">Photos For Shops Main Category Banner <span>(This will be your Products photo )</span> </label>
		<div class="col-sm-9">
			<h5><i class="fa fa-upload" aria-hidden="true"></i>Select Files to Upload.<span>You can add multiple images (Maximum Two).</span></h5>
			<div class="upload-section">
				<input type="file" name="files[]" id="filer_input2" multiple="multiple">
				<input type="hidden" name="ShopCategory[category_image]" value="" id="category_image" >

			</div>
		</div>
	</div>-->


	<div id ="sub_category_name" class="row">

	</div>


	<div class="row form-group add-image">
		<label class="col-sm-3 label-title">Photos For Shops Sub Category</label>
		<div class="col-sm-9">
			<h5><i class="fa fa-upload" aria-hidden="true"></i>Select Files to Upload.</h5>
			<div class="upload-section">
				<input type="file" name="files[]" id="filer_input3" multiple="multiple">
				<input type="hidden" name="ShopCategory[sub_category_image]" value="" id="sub_category_image" >
				<!--<input type="file" id="upload-image-one" name="ads_image[]" multiple>-->
			</div>
		</div>
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

	$("#shop_category").change(function() {

			var category_name = ( $('option:selected', this).val() );

			$.ajax({
				type:"POST",
				dataType:"json",
				url:SITE_URL + "site/LoadSubCategoryForProduct",
				data:{category_name:category_name},
				success: function (data) {
					$("#sub_category_name").html(data.html);
				},
				error:function(){
					console.log('ajax error')}
			});
		});



</script>