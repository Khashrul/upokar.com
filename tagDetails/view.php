<?php
/* @var $this TagDetailsController */
/* @var $model TagDetails */

$this->breadcrumbs=array(
	'Tag Details'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List TagDetails', 'url'=>array('index')),
	array('label'=>'Create TagDetails', 'url'=>array('create')),
	array('label'=>'Update TagDetails', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete TagDetails', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TagDetails', 'url'=>array('admin')),
);
?>

<h1>View TagDetails #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'parent_service_id',
		'sub_service_id',
		'tag_name',
		'tag_price',
		'create_date',
		'update_date',
	),
)); ?>
