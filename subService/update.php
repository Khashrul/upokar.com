<?php
/* @var $this SubServiceController */
/* @var $model SubService */

$this->breadcrumbs=array(
	'Sub Services'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SubService', 'url'=>array('index')),
	array('label'=>'Create SubService', 'url'=>array('create')),
	array('label'=>'View SubService', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage SubService', 'url'=>array('admin')),
);
?>

<h1>Update SubService <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>