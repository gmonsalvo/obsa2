<?php

if (isset($_POST['Cheques']['fechaInicio']))
    $modelo->fechaInicio = $_POST['Cheques']['fechaInicio'];
else if (isset($_GET['Cheques']['fechaInicio']))
	$modelo->fechaInicio = $_GET['Cheques']['fechaInicio'];
else
	$modelo->fechaInicio = Date('d/m/Y');

if (isset($_POST['Cheques']['fechaFinal']))
    $modelo->fechaFinal = $_POST['Cheques']['fechaFinal'];
else if (isset($_GET['Cheques']['fechaFinal']))
	$modelo->fechaFinal = $_GET['Cheques']['fechaFinal'];
else
	$modelo->fechaFinal = Date('d/m/Y');

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('cheques-grid', {
		data: $(this).serialize()
	});
	
	$.ajax({
	type: 'GET',
	url:'".$this->createUrl('cheques/calcularTotal')."?fechaInicio='+$('#fechaInicio').val()+'&fechaFinal='+$('#fechaFinal').val(),
	data:{val:this.value},
	success: function(data){
	$('#txtTotal').val(data);
	},
	});	
	
	return false;
});
");

Yii::app()->clientScript->registerScript('procesar', "
	$('#btnAcreditar').click(function(){
		$('#procesar').val('1');
	});
");

?>

<script>
	function filaSeleccionada() {
		
		$.ajax({
		type: 'GET',
		url:'<?php echo $this->createUrl('cheques/calcularTotal')?>?fechaInicio='+$('#fechaInicio').val()+'&fechaFinal='+$('#fechaFinal').val()+'&chequesSeleccionados='+$.fn.yiiGridView.getSelection('cheques-grid'),
		data:{val:this.value},
		success: function(data){
		$('#txtTotal').val(data);
		},
		});	
	}
</script>

<h1>Listado de Cheques Comprados a Financieras</h1>

<div class="search-form">
<?php $this->renderPartial('_searchChequesClientes',array(
	'modelo'=>$modelo, 'modeloOperacionesCheques'=>$modeloOperacionesCheques
)); ?>
</div><!-- search-form -->

<?php $formProcesar=$this->beginWidget('CActiveForm', array(
	'id' => 'frmChequesFinanciera',
	'method'=>'post',
	'enableClientValidation'=>true,
)); ?>

<div class="row">
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cheques-grid',
	'dataProvider'=>$modelo->searchByFechaAndEstado2(),
	'selectableRows' => '9999',
	'selectionChanged' => 'filaSeleccionada',
	//'filter'=>$modelo,
	'columns'=>array(
		array(
            'header' => '',
            'id' => 'idCheques',
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
            'htmlOptions'=>array('style' => 'text-align: right;'),
        ),
         array(
            'name' => 'montoOrigen',
            'header' => 'Importe',
            'value' => '"$ ".number_format($data->montoOrigen,2)',
            'htmlOptions'=>array('style' => 'text-align: right;'),
        ),
         array(
            'name' => 'montoNeto',
            'header' => 'Importe Neto',
            'value' => '"$ ".number_format($data->montoNeto,2)',
            'htmlOptions'=>array('style' => 'text-align: right;'),
        ),
	),
)); ?>
</div>

<div class="row buttons" style='text-align: right;'>
	<!--
	<span style='padding-right: 60px;'>
	<?php echo CHtml::submitButton('Acreditar', array('id'=>'btnAcreditar')); ?>
	<?php echo CHtml::hiddenField('procesar', '0', array('type'=>"hidden")); ?>
	</span>-->
	<span>
	<?php echo CHtml::label('Monto Total', 'lblMontoTotal', array('')); ?>
	<?php echo CHtml::textField('montoTotal','', array('id'=>'txtTotal', 'style'=>'text-align: right')); ?>
	</span>
</div>
<div class="row buttons" style='text-align: right;'>
	<?php echo CHtml::error($modelo, 'numeroCheque'); ?>
</div>
<br>
<?php
	$this->beginWidget('zii.widgets.CPortlet', array(
        'title' => '',
    ));
    echo "<b>Acciones</b>";
    $this->endWidget();
?>

<?php $this->endWidget(); ?>
