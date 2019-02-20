<?php
/* @var $this UserRegisterController */
/* @var $model UserRegister */

$this->breadcrumbs=array(
	'User Registers'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List UserRegister', 'url'=>array('index')),
	array('label'=>'Create UserRegister', 'url'=>array('create')),
	array('label'=>'View UserRegister', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage UserRegister', 'url'=>array('admin')),
);
?>

<h1>Update UserRegister <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>