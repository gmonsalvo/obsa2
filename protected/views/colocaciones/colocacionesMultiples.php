<?php Yii::app()->clientScript->registerScript('setFocus', '$("#clearing").focus();'); ?>
<script>
function CalcularValorActual() {
	var chequesSeleccionados = $.fn.yiiGridView.getSelection("cheques-grid");
	if(chequesSeleccionados.length > 0 && $("#tasa").val()!= "") {
        $.ajax({
            type: 'POST',
            url: "<?php echo CController::createUrl('colocaciones/calculoValorActualMultiplesCheques') ?>",
            data:{'idCheque':$.fn.yiiGridView.getSelection("cheques-grid"), 'montoColocado': 1000, 'tasa': $( "#tasa" ).val(), "clearing": $("#clearing").val()},
            dataType: 'Text',
            success:function(data){
            	var data = jQuery.parseJSON(data);
                $("#montoValorActualPorColocar").val(data.totalValorActual);
                $("#montoValorNominalPorColocar").val(data.totalValorNominal);
                if($.fn.yiiGridView.getSelection("clientes-grid").length > 0)
                	$("#submitBoton").removeAttr("disabled");
                verificarSaldoCliente();		        
            }
        });
    }
    else {
    	$("#montoValorActualPorColocar").val(0);
        $("#montoValorNominalPorColocar").val(0);
    	$("#submitBoton").attr("disabled","disabled");
    }
}

function getDatosCliente(){
	var clienteId = $.fn.yiiGridView.getSelection("clientes-grid");
    $.ajax({
        type: 'POST',
        url: "<?php echo CController::createUrl('clientes/getCliente') ?>",
        data:{'id':clienteId},
        dataType: 'text',
        success:function(data){
            var datos=jQuery.parseJSON(data);
            var cliente=datos.cliente;
            $("#tasa").val(cliente.tasaInversor);
            $("#saldoCliente").val(datos.saldo);
            verificarSaldoCliente();
        }
    });
}

function verificarSaldoCliente() {
	var saldo = parseFloat($("#saldoCliente").val());
    if(saldo < parseFloat($("#montoValorActualPorColocar").val())) {
    	alert("El cliente seleccionado no tiene saldo suficiente para realizar las colocaciones seleccionadas");
    	$("#submitBoton").attr("disabled","disabled");
    } else {
    	HabilitarColocacion();
    }
}

function VerificarMascara(obj)
{
    var valor=$(obj).val().replace( "_", "0");
    valor=valor.replace( "_", "0");
    $(obj).val(valor);
}

function HabilitarColocacion() {
	var clientesGrid = $.fn.yiiGridView.getSelection("clientes-grid");
	var chequesGrid = $.fn.yiiGridView.getSelection("cheques-grid");
	if(clientesGrid.length > 0 && chequesGrid.length > 0) {
        $("#submitBoton").removeAttr("disabled");
    } else
    	$("#submitBoton").attr("disabled","disabled");
}
</script>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'colocaciones-form',
        'enableAjaxValidation' => false,
            ));
    ?>

    <table style="width: 60%">
    	<tr>
    		<td><?php echo CHtml::label("Fecha", 'fecha'); ?></td>
    		<td><?php echo CHtml::label("Clearing", 'clearing'); ?></td>
    		<td><?php echo CHtml::label("Tasa", 'tasa'); ?></td>
    	</tr>
    	<tr>
    		<td><?php echo CHtml::textField('fecha', date('d/m/Y') , array('id' => 'fecha','readonly' => 'readonly')); ?></td>
    		<td><?php echo CHtml::textField('clearing', 0 , array('id' => 'clearing', 'onchange' => "CalcularValorActual()")); ?></td>
    		<td>
				<?php
                $this->widget('CMaskedTextField', array(
                    'name' => 'tasa',
                    'mask' => '99.99',
                    'htmlOptions' => array(
                    	'id' => 'tasa',
                        'onblur' => 'js:VerificarMascara(this);',
                        'style' => 'width:100px;',
                        // 'value' => Yii::app()->user->model->sucursal->tasaDescuentoGeneral,
                    ),
                ));
                ?>

    			<?php //echo CHtml::textField('tasa', '' , array('id' => 'tasa', 'onchange' => "CalcularValorActual()")); ?></td>
    	</tr>
    </table>

    <?php
    $this->beginWidget('zii.widgets.CPortlet', array(
        'id' => 'portletInversores',
        'title' => '',
    ));
    echo "<b>Inversores</b>";
    $this->endWidget("portletInversores");
    ?>

    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'clientes-grid',
        'dataProvider' => $clientes->searchInversoresParaColocacion(true),
        'filter' => $clientes,
        'selectionChanged' => 'getDatosCliente',
        'selectableRows' => 1,
        'columns' => array(
        	array(
	            'header' => '',
	            'class' => 'CCheckBoxColumn',
	            'id' =>'clienteId'
	        ),
            'razonSocial',
            array(
                'name' => 'saldo',
                'header' => 'Saldo',
                'value' => 'Utilities::MoneyFormat($data->saldo)',
            ),
            array(
                'name' => 'porcentajeInversion',
                'header' => 'Porc. Disponibilidad',
                'value' => '$data->porcentajeInversion',
            ),
            'tasaInversor',
            array(
                'name' => 'operadorId',
                'header' => 'Operador',
                'value' => '$data->operador->apynom',
            ),
        ),
        'htmlOptions' => array(
            'class' => 'grid-view mgrid_table',
        ),
    ));
	?>

    <?php
    $this->beginWidget('zii.widgets.CPortlet', array(
        'id' => 'portletCheques',
        'title' => '',
    ));
    echo "<b>Cheques en cartera</b>";
    $this->endWidget("portletCheques");
    ?>

	<?php $this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'cheques-grid',
		'dataProvider'=>$chequesDataProvider,
		'filter' => $cheques,
		'selectableRows' => -1,
		'selectionChanged'=>'CalcularValorActual',
		'columns'=>array(
		    array(
				'header'=>'Cheques seleccionados',
	            'class'=>'CCheckBoxColumn',
	            'id' => 'chequesSeleccionados'            
	        ),
			'numeroCheque',
			'fechaPago',
	            
	                array(
				'name'=>'montoOrigen',
				'header'=>'Monto Nominal',
				'value'=>'Utilities::MoneyFormat($data->montoOrigen)',
			),
	                array(
				'name'=>'libradorId',
				'header'=>'Librador',
				'value'=>'$data->librador->denominacion',
			),
			array(
				'name'=>'operacionChequeId',
				'header'=>'Cliente Tomador',
				'value'=>'$data->operacionCheque->cliente->razonSocial',
			),
			array(
				'name'=>'tipoCheque',
				'header'=>'Tipo Cheque',
				'value'=>'$data->getTypeDescription("tipoCheque")',
			),
			array(
				'name'=>'estado',
				'header'=>'Estado',
				'value'=>'$data->getTypeDescription("estado")',
			),
		),
	)); ?>

	<table style="width: 40%">
		<tr>
			<td>
				<?php echo CHtml::label('Valor Actual por colocar', 'montoValorActualPorColocar'); ?>
			</td>
			<td>
				<?php echo CHtml::label('Valor Nominal por colocar', 'montoValorNominalPorColocar'); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php echo CHtml::textField('montoValorActualPorColocar', '0', array('id' => 'montoValorActualPorColocar', 'size' => 15, 'maxlength' => 15, 'readonly' => 'readonly')); ?>
			</td>
			<td>
				<?php echo CHtml::textField('montoValorNominalPorColocar', '0', array('id' => 'montoValorNominalPorColocar', 'size' => 15, 'maxlength' => 15, 'readonly' => 'readonly')); ?>
			</td>
		</tr>	
	</table>

    <div class="row buttons">
    	<?php echo CHtml::hiddenField("saldoCliente","0",array("id" => "saldoCliente")) ?>
        <?php echo CHtml::submitButton('Cerrar colocacion', array('name'=>'submitBoton','id' => 'submitBoton', 'disabled' => 'disabled')); ?>
    </div>

    <?php $this->endWidget("colocaciones-form"); ?>
</div>        
