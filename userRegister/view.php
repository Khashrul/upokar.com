<?php
/* @var $this UserRegisterController */
/* @var $model UserRegister */

$this->breadcrumbs=array(
	'User Registers'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List UserRegister', 'url'=>array('index')),
	array('label'=>'Create UserRegister', 'url'=>array('create')),
	array('label'=>'Update UserRegister', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UserRegister', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UserRegister', 'url'=>array('admin')),
);
?>

<h1>View UserRegister #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_name',
		'user_email',
		'user_password',
		'user_mobile_number',
		'user_token',
		'user_image',
		'user_address',
		'user_role',
	),
)); ?>
