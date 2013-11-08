<?php
/* @var $this TipoTasaController */
/* @var $model TipoTasa */

$this->breadcrumbs=array(
	'Tipo Tasas'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List TipoTasa', 'url'=>array('index')),
	array('label'=>'Create TipoTasa', 'url'=>array('create')),
	array('label'=>'View TipoTasa', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage TipoTasa', 'url'=>array('admin')),
);
?>

<h1>Update TipoTasa <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>