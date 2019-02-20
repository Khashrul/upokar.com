<?php
/* @var $this SubServiceController */
/* @var $model SubService */

$this->breadcrumbs=array(
	'Sub Services'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List SubService', 'url'=>array('index')),
	array('label'=>'Create SubService', 'url'=>array('create')),
	array('label'=>'Update SubService', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SubService', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SubService', 'url'=>array('admin')),
);
?>

<h1>View SubService #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'parent_service_id',
		'service_name',
		'service_image',
		'initial_price',
		'service_description',
		'service_condition',
		'replace_policy',
		'review',
		'create_date',
		'update_date',
	),
)); ?>
