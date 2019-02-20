<?php
/* @var $this ShopCategoryController */
/* @var $model ShopCategory */

$this->breadcrumbs=array(
	'Shop Categories'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List ShopCategory', 'url'=>array('index')),
	array('label'=>'Create ShopCategory', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#shop-category-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Shop Categories</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'shop-category-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'category_name',
		'category_image',
		'sub_category_name',
		'sub_category_image',
		'create_date',
		/*
		'update_date',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
