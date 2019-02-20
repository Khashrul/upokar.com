<?php
/* @var $this TagDetailsController */
/* @var $model TagDetails */

$this->breadcrumbs=array(
	'Tag Details'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List TagDetails', 'url'=>array('index')),
	array('label'=>'Create TagDetails', 'url'=>array('create')),
	array('label'=>'View TagDetails', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage TagDetails', 'url'=>array('admin')),
);
?>

<h1>Update TagDetails <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>