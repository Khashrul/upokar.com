<?php
/* @var $this ShopCategoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Shop Categories',
);

$this->menu=array(
	array('label'=>'Create ShopCategory', 'url'=>array('create')),
	array('label'=>'Manage ShopCategory', 'url'=>array('admin')),
);
?>

<h1>Shop Categories</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
