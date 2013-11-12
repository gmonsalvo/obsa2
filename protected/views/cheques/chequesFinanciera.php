<?php

if (isset($_POST['Cheques']['fechaPago']))
    $modelo->fechaPago = $_POST['Cheques']['fechaPago'];
else if (isset($_GET['Cheques']['fechaPago']))
	$modelo->fechaPago = $_GET['Cheques']['fechaPago'];
else
	$modelo->fechaPago = Date('d/m/Y');

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
<?php $this->renderPartial('_searchClientes',array(
	'modelo'=>$modelo, 'modeloOperacionesCheques'=>$modeloOperacionesCheques
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cheques-grid',
	'dataProvider'=>$modelo->searchByFechaAndEstado2(),
	'filter'=>$modelo,
	'columns'=>array(
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
            'header' => 'Fecha Vto.',
            'value' => 'Utilities::viewDateFormat($data->fechaPago)',
        ),
         array(
            'name' => 'montoOrigen',
            'header' => 'Importe',
            'value' => '"$ ".number_format($data->montoOrigen,2)',
        ),

	),
)); ?>
