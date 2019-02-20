<?php
/* @var $this UserRegisterController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'User Registers',
);

$this->menu=array(
	array('label'=>'Create UserRegister', 'url'=>array('create')),
	array('label'=>'Manage UserRegister', 'url'=>array('admin')),
);
?>

<h1>User Registers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
