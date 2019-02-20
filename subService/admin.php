<?php
/* @var $this SubServiceController */
/* @var $model SubService */

$this->breadcrumbs=array(
	'Sub Services'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List SubService', 'url'=>array('index')),
	array('label'=>'Create SubService', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#sub-service-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Sub Services</h1>

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
	'id'=>'sub-service-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'parent_service_id',
		'service_name',
		array(
			'name'=>'service_image',
			'value'=>'Generic::showImage("service_image", $data->service_image)',
			'type' => 'raw',
			'filter' => false,
		),
		'initial_price',
		//'service_description',
		//'service_condition',
		//'replace_policy',
		//'review',
		'create_date',
		//'update_date',

		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
