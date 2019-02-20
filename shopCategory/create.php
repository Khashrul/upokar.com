<?php
/* @var $this ShopCategoryController */
/* @var $model ShopCategory */

$this->breadcrumbs=array(
	'Shop Categories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ShopCategory', 'url'=>array('index')),
	array('label'=>'Manage ShopCategory', 'url'=>array('admin')),
);
?>

<h1>Create ShopCategory</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>