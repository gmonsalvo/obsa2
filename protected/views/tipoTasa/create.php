<?php
/* @var $this TipoTasaController */
/* @var $model TipoTasa */

$this->breadcrumbs=array(
	'Tipo Tasas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TipoTasa', 'url'=>array('index')),
	array('label'=>'Manage TipoTasa', 'url'=>array('admin')),
);
?>

<h1>Create TipoTasa</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>