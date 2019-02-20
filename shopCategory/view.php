<?php
/* @var $this ShopCategoryController */
/* @var $model ShopCategory */

$this->breadcrumbs=array(
	'Shop Categories'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ShopCategory', 'url'=>array('index')),
	array('label'=>'Create ShopCategory', 'url'=>array('create')),
	array('label'=>'Update ShopCategory', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ShopCategory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ShopCategory', 'url'=>array('admin')),
);
?>

<h1>View ShopCategory #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'category_name',
		'category_image',
		'sub_category_name',
		'sub_category_image',
		'create_date',
		'update_date',
	),
)); ?>
