<?php
/* @var $this SubServiceController */
/* @var $model SubService */

$this->breadcrumbs=array(
	'Sub Services'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List SubService', 'url'=>array('index')),
	array('label'=>'Manage SubService', 'url'=>array('admin')),
);
?>

<h1>Create SubService</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>