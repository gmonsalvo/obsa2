<?php
$this->breadcrumbs=array(
	'Presupuestos Cheques'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List PresupuestosCheques', 'url'=>array('index')),
	array('label'=>'Create PresupuestosCheques', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('presupuestos-cheques-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Presupuestos Cheques</h1>

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
	'id'=>'presupuestos-cheques-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'operacionChequeId',
		'tasaDescuento',
		'clearing',
		'pesificacion',
		'numeroCheque',
		/*
		'libradorId',
		'bancoId',
		'montoOrigen',
		'fechaPago',
		'tipoCheque',
		'endosante',
		'montoNeto',
		'estado',
		'userStamp',
		'timeStamp',
		'sucursalId',
		'montoGastos',
		'tieneNota',
		'comisionado',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>