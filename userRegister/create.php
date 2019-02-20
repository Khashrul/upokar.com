<?php
/* @var $this UserRegisterController */
/* @var $model UserRegister */

$this->breadcrumbs=array(
	'User Registers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List UserRegister', 'url'=>array('index')),
	array('label'=>'Manage UserRegister', 'url'=>array('admin')),
);
?>

<h1>Create UserRegister</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>