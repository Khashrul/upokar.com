<?php
/* @var $this SubServiceController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Sub Services',
);

$this->menu=array(
	array('label'=>'Create SubService', 'url'=>array('create')),
	array('label'=>'Manage SubService', 'url'=>array('admin')),
);
?>

<h1>Sub Services</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
