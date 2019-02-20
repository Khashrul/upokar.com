<?php
/* @var $this TagDetailsController */
/* @var $model TagDetails */

$this->breadcrumbs=array(
	'Tag Details'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TagDetails', 'url'=>array('index')),
	array('label'=>'Manage TagDetails', 'url'=>array('admin')),
);
?>

<h1>Create TagDetails</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>