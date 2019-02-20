<?php
/* @var $this TagDetailsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tag Details',
);

$this->menu=array(
	array('label'=>'Create TagDetails', 'url'=>array('create')),
	array('label'=>'Manage TagDetails', 'url'=>array('admin')),
);
?>

<h1>Tag Details</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
