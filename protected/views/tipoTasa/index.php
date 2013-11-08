<?php
/* @var $this TipoTasaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tipo Tasas',
);

$this->menu=array(
	array('label'=>'Create TipoTasa', 'url'=>array('create')),
	array('label'=>'Manage TipoTasa', 'url'=>array('admin')),
);
?>

<h1>Tipo Tasas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
