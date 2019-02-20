<?php
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);

$baseUrl = Yii::app()->request->baseUrl;
?>
<div class="main-form" >
<h1>Site Administrator Login</h1>
	*******************************************

<p >Please fill out the following form with your login credentials:</p>

<div class="form" >
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'login-form',
		'enableAjaxValidation'=>true,
	)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>

	</div>

<!--	<div class="row rememberMe">
        <?php /*echo $form->checkBox($model,'rememberMe'); */?>
        <?php /*echo $form->label($model,'rememberMe'); */?>
        <?php /*echo $form->error($model,'rememberMe'); */?>
    </div>-->

	<div class="row submit">
		<?php echo CHtml::submitButton('Login'); ?>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->
</div>

<style>
	.errorMessage{
		color: red;
	}
	.main-form{
		width: auto;
		height: 500px;
		padding-top: 50px;
		padding-left: 550px
	}
	.form{
		width: 400px;
		height: 500px;
		border: 1px solid;
		padding-top: 50px;
		padding-left: 50px;
		padding-right: 50px

	}
</style>
