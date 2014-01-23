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
	calcularNeto(1);
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
		calcularNeto(1);
		},
		});	
	}

	function pesificadorSeleccionado() {
		
		$.ajax({
		type: 'GET',
		url:'<?php echo $this->createUrl('cheques/buscarTasa')?>?pesificadorId='+$('#Cheques_pesificadorId').val(),
		data:{val:this.value},
		success: function(data){
		$('#Cheques_tasaPesificacion').val(data);
		calcularNeto(1);
		},
		});	
	}
	
	function calcularNeto(tipo) {
		
		monto = $('#txtTotal').val();
		
		monto = monto.replace('$','');
		monto = monto.replace('.','');
		monto = monto.replace(/,/,'.');
		
		valorCheques = parseFloat(monto);
		
		if (tipo == 1) {
			tasa = parseFloat($('#Cheques_tasaPesificacion').val());
			
			$("#monto").val()-$("#monto").val()*$("#porcentajePesific").val()/100
			
			neto = valorCheques - ((valorCheques * tasa) / 100);
			if (!isNaN(neto))
				parseFloat($('#Cheques_netoPesificacion').val(neto.toFixed(2)));
		}
	}
	
	function enviar(tipo) {
		
		if (($('#txtTotal').val() == null) || ($('#txtTotal').val() == '')) {
			alert('Debe seleccionar los cheques a procesar');
			return;
		}
		
		if (tipo == 1) {
			if (($('#Cheques_tasaPesificacion').val() == null) || ($('#Cheques_tasaPesificacion').val() == '')) {
				alert('Debe especificar la tasa');
				$('#Cheques_tasaPesificacion').focus();
				return;
			}
			$('#Cheques_accion').val('1');
			$('#frmChequesFinanciera').submit();
		}
		if (tipo == 2) {
			$('#Cheques_accion').val('2');
			$('#frmChequesFinanciera').submit();
		}
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

<?php echo $formProcesar->hiddenField($modelo,'accion') ?>

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
	<?php echo CHtml::textField('montoTotal','', array('id'=>'txtTotal', 'style'=>'text-align: right', 'readOnly' => 'true')); ?>
	</span>
</div>
<div class="row buttons" style='text-align: right;'>
	<?php echo CHtml::error($modelo, 'numeroCheque'); ?>
</div>
<?php
	$this->beginWidget('zii.widgets.CPortlet', array(
        'title' => '',
    ));
    echo "<b>Acciones</b>";
    $this->endWidget();
	
	//$pesificadores = Pesificadores::model()->findAllByAttributes(array(), 'UPPER(denominacion) LIKE UPPER("%BARBIERI%")', array()); 
	$pesificadores = Pesificadores::model()->findAll();
?>
<br>
<div class="row">
	<b>1) PESIFICADOR</b><br><br>
</div>
<div class="row" style='border-bottom: 3px solid #b7d6e7;'>
	<table>
		<tr>
			<td style='text-align: left; width: 350px;'>
				<?php echo $formProcesar->labelEx($modelo,'pesificadorId'); ?>
				<?php echo $formProcesar->dropDownList($modelo,'pesificadorId', CHtml::listData($pesificadores, 'id', 'denominacion'), array('onChange' => 'pesificadorSeleccionado();')); ?>
				<?php echo $formProcesar->error($modelo,'pesificadorId'); ?>
			</td>
			<td style='text-align: left; width: 150px;'>
				<?php echo $formProcesar->labelEx($modelo,'tasaPesificacion'); ?>
				<?php echo $formProcesar->textField($modelo,'tasaPesificacion',array('size'=>5, 'maxlength'=>5, 'onblur'=>'calcularNeto(1);', 'onchange'=>'calcularNeto(1);', 'onkeyup'=>'calcularNeto(1)', 
													'style'=>'text-align:right;', 'value' => $pesificadores[0]->tasaDescuento, 'readOnly' => 'true')) ?>
				<?php echo $formProcesar->error($modelo,'tasaPesificacion'); ?>
			</td>
			<td style='text-align: left; width: 220px;'>
				<?php echo $formProcesar->labelEx($modelo,'netoPesificacion'); ?>
				<?php echo $formProcesar->textField($modelo,'netoPesificacion',array('size'=>15,'maxlength'=>15, 'style'=>'text-align:right;', 'readOnly'=>'true')); ?>
				<?php echo $formProcesar->error($modelo,'netoPesificacion'); ?>
			</td>
			<td style='text-align: right;'>
				<?php echo CHtml::button('Procesar', array('onclick'=>'js:enviar(1);')); ?>
			</td>
		</tr>
	</table>
</div>
<br>
<div class="row">
	<b>2) ACREDITAR EN FINANCIERA DE ORIGEN</b><br><br>
</div>
<div class="row" style='border-bottom: 3px solid #b7d6e7;'>
	<table>
		<tr>
			<td>
			<?php echo CHtml::button('Procesar', array('onclick'=>'js:enviar(2);')); ?>
			</td>
		</tr>
	</table>
</div>
<br>
<div class="row">
	<b>3) ACREDITAR EN OTRA FINANCIERA</b><br><br>
</div>
<div class="row" style='border-bottom: 3px solid #b7d6e7;'>
	<table>
		<tr>
			<td style='text-align: left; width: 350px;'>
				<?php echo $formProcesar->labelEx($modelo,'financieraId'); ?>
				<?php echo $formProcesar->dropDownList($modelo,'financieraId', CHtml::listData(Clientes::model()->findAllByAttributes(array(), 'tipoCliente = :tipoCliente', array('tipoCliente' => Clientes::TYPE_FINANCIERA)), 'id', 'razonSocial')); ?>
				<?php echo $formProcesar->error($modelo,'financieraId'); ?>
			</td>
			<td style='text-align: left; width: 150px;'>
				<?php echo $formProcesar->labelEx($modelo,'costoFinanciera'); ?>
				<?php echo $formProcesar->textField($modelo,'costoFinanciera',array('size'=>5,'maxlength'=>5)); ?>
				<?php echo $formProcesar->error($modelo,'costoFinanciera'); ?>
			</td>
			<td style='text-align: right;'>
				<?php echo CHtml::button('Procesar', array('onclick'=>'js:enviar(3);')); ?>
			</td>
		</tr>
	</table>
</div>
<br>
<div class="row">
	<b>4) CLIENTE INVERSOR</b><br><br>
</div>
<div class="row" style='border-bottom: 3px solid #b7d6e7;'>
	<table>
		<tr>
			<td style='text-align: left; width: 350px;'>
				<?php echo $formProcesar->labelEx($modelo,'inversorId'); ?>
				<?php echo $formProcesar->dropDownList($modelo,'inversorId', CHtml::listData(Clientes::model()->findAllByAttributes(array(), '(tipoCliente = :tipoCliente1) OR (tipoCliente = :tipoCliente2)', array('tipoCliente1' => Clientes::TYPE_INVERSOR, 'tipoCliente2' => Clientes::TYPE_TOMADOR_E_INVERSOR)), 'id', 'razonSocial')); ?>
				<?php echo $formProcesar->error($modelo,'inversorId'); ?>
			</td>
			<td style='text-align: left; width: 220px;'>
				<?php echo $formProcesar->labelEx($modelo,'porcentajeReconocimiento'); ?>
				<?php echo $formProcesar->textField($modelo,'porcentajeReconocimiento',array('size'=>5,'maxlength'=>5)); ?>
				<?php echo $formProcesar->error($modelo,'porcentajeReconocimiento'); ?>
			</td>
			<td style='text-align: left; width: 220px;'>
				<?php echo $formProcesar->labelEx($modelo,'netoInversor'); ?>
				<?php echo $formProcesar->textField($modelo,'netoInversor',array('size'=>15,'maxlength'=>15)); ?>
				<?php echo $formProcesar->error($modelo,'netoInversor'); ?>
			</td>
			<td style='text-align: right;'>
				<?php echo CHtml::button('Procesar', array('onclick'=>'js:enviar(4);')); ?>
			</td>
		</tr>
	</table>
</div>

<?php $this->endWidget(); ?>
