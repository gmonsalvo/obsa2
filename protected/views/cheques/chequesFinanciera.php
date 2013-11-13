<?php

if (isset($_POST['Cheques']['fechaPago']))
    $modelo->fechaPago = $_POST['Cheques']['fechaPago'];
else if (isset($_GET['Cheques']['fechaPago']))
	$modelo->fechaPago = $_GET['Cheques']['fechaPago'];
else
	$modelo->fechaPago = Date('d/m/Y');

if (isset($_POST['OperacionesCheques']['clienteId']))
    $modelo->clienteId = $_POST['OperacionesCheques']['clienteId'];
else if (isset($_GET['OperacionesCheques']['clienteId']))
	$modelo->clienteId = $_GET['OperacionesCheques']['clienteId'];
else
	$modelo->clienteId = '';


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('cheques-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Listado de Cheques Comprados a Financiera</h1>

<div class="search-form">
<?php $this->renderPartial('_searchChequesClientes',array(
	'modelo'=>$modelo, 'modeloOperacionesCheques'=>$modeloOperacionesCheques
)); ?>
</div><!-- search-form -->

<div class="row">
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cheques-grid',
	'dataProvider'=>$modelo->searchByFechaClienteAndEstado(),
	//'filter'=>$modelo,
	'columns'=>array(
		array(
            'header' => '',
            'class' => 'CCheckBoxColumn',
        ),		
		'numeroCheque',
		array(
			'name'=>'bancoId',
			'header'=>'Banco',
			'value'=>'$data->banco->nombre',
		),
		array(
	            'name' => 'operacionChequeId',
	            'header' => 'Comprado a',
	            'value' => '$data->operacionCheque->cliente->razonSocial',
	    ),

	    array(
	            'name' => 'librador_denominacion',
	            'header' => 'Librador',
	            'value' => '$data->librador->denominacion',
	    ),
        array(
            'name' => 'fechaPago',
            'header' => 'Fecha Pago',
            'value' => 'Utilities::viewDateFormat($data->fechaPago)',
        ),
         array(
            'name' => 'montoOrigen',
            'header' => 'Importe',
            'value' => '"$ ".number_format($data->montoOrigen,2)',
        ),
         array(
            'name' => 'montoNeto',
            'header' => 'Importe Neto',
            'value' => '"$ ".number_format($data->montoNeto,2)',
        ),
	),
)); ?>
</div>
<div class="row buttons" style='text-align: right;'>
	<?php echo CHtml::submitButton('Acreditar'); ?>
	<?php echo CHtml::label('Monto Total', 'lblMontoTotal', array('')); ?>
	<?php echo CHtml::textField('montoTotal',$modelo->obtenerTotal()); ?>
</div>

