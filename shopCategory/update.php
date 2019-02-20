<?php
/* @var $this ShopCategoryController */
/* @var $model ShopCategory */

$this->breadcrumbs=array(
	'Shop Categories'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ShopCategory', 'url'=>array('index')),
	array('label'=>'Create ShopCategory', 'url'=>array('create')),
	array('label'=>'View ShopCategory', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ShopCategory', 'url'=>array('admin')),
);
?>

<h1>Update ShopCategory <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>