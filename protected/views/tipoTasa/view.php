<?php
/* @var $this TipoTasaController */
/* @var $model TipoTasa */

$this->breadcrumbs=array(
	'Tipo Tasas'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List TipoTasa', 'url'=>array('index')),
	array('label'=>'Create TipoTasa', 'url'=>array('create')),
	array('label'=>'Update TipoTasa', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete TipoTasa', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TipoTasa', 'url'=>array('admin')),
);
?>

<h1>View TipoTasa #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nombre',
		'userStamp',
		'timeStamp',
	),
)); ?>
