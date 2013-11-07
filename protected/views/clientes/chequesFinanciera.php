<script>
	    function getDatosCliente(){

        if($("#OperacionesCheques_clienteId").val()!=""){
            $.ajax({
                type: 'POST',
                url: "<?php echo CController::createUrl('clientes/getCliente') ?>",
                data:{'id':$("#OperacionesCheques_clienteId").val()},
                dataType: 'text',
                success:function(data){
                    var datos=jQuery.parseJSON(data);
                    var cliente=datos.cliente;
                    $("#clienteId").val($("#OperacionesCheques_clienteId").val());
                }
            });
        }
    }
</script>

<?php
if (isset($_POST['clienteId']) && isset($_POST['fecha'])) {
    $clienteId = $_POST['clienteId'];
    $fecha = $_POST['fecha'];
}
else {
    if (isset($_GET['clienteId']) && isset($_GET['fecha'])) {
        $clienteId = $_GET['clienteId'];
        $fecha = $_GET['fecha'];
    }
    else {
        $clienteId = '';
        $fecha = Date('d/m/Y');
    }
}
?>


<?php
$form=$this->beginWidget('CActiveForm', array('action'=>Yii::app()->createUrl($this->route), 'method'=>'get', ));
?>

<div class="form">
    <table>
        <tr>
            <td><?php echo CHtml::label("Fecha", 'fecha'); ?></td>
            <td><?php
				    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
							        // you must specify name or model/attribute
							        'model' => $modeloOperacionesCheques,
							        'attribute' => 'fecha',
							        'language' => 'es',
							        'options' => array(
							            'dateFormat' => 'dd/mm/yy',
							            //'defaultDate'=>Date('d-m-Y'),
							            'changeMonth' => 'true',
							            'changeYear' => 'true',
							            'showButtonPanel' => 'true',
							            'constrainInput' => 'false',
							            'duration' => 'fast',
							            'showAnim' => 'fold',
							        ),
							        'htmlOptions' => array(
							            'value' => $fecha,
							            'readonly' => "readonly",
							            'style' => 'height:20px;',
							            'onChange' => 'js:$("#OperacionesCheques_fecha").focus(); $("#fecha").val($("#OperacionesCheques_fecha").val())',
							        )
            					)
    				);
    			?>
            </td>
            <td>
                <?php echo CHtml::label("Cliente", 'clienteId'); ?></td>
            <td><?php
                $this->widget('CustomEJuiAutoCompleteFkField', array(
                    'model' => $modeloOperacionesCheques,
                    'attribute' => 'clienteId', //the FK field (from CJuiInputWidget)
                    // controller method to return the autoComplete data (from CJuiAutoComplete)
                    'sourceUrl' => Yii::app()->createUrl('/clientes/buscarRazonSocial', array("tipo[0]" => Clientes::TYPE_FINANCIERA)),
                    // defaults to false.  set 'true' to display the FK field with 'readonly' attribute.
                    'showFKField' => false,
                    // display size of the FK field.  only matters if not hidden.  defaults to 10
                    'FKFieldSize' => 15,
                    'relName' => 'cliente', // the relation name defined above
                    'displayAttr' => 'razonSocial', // attribute or pseudo-attribute to display
                    // length of the AutoComplete/display field, defaults to 50
                    'autoCompleteLength' => 30,
                    // any attributes of CJuiAutoComplete and jQuery JUI AutoComplete widget may
                    // also be defined.  read the code and docs for all options
                    'options' => array(
                        // number of characters that must be typed before
                        // autoCompleter returns a value, defaults to 2
                        'minLength' => 1,
                    ),
                    'select' => 'getDatosCliente();',
                    'htmlOptions' => array(
                        'tabindex' => 1),
                    'onSelectScript'=>CHtml::ajax(array('type'=>'POST', 'url'=>array("cheques/cargarChequesCliente"), 'update'=>'#contenedor')),
                ));
                ?>
            </td>
        </tr>
    </table>
</div>


<div class="form">
    <?php echo $form->hiddenField($modeloOperacionesCheques, 'clienteId', array("id"=>"clienteId",'value' => '')); ?>
	<h2><b>Listado de Cheques</b></h2>
	<?php	$this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'cheques-grid',
			'dataProvider'=>$modeloCheques->searchChequesColocadosPorInversor($clienteId),
			'filter'=>$modeloCheques,
		    //'selectionChanged' => 'AccionGridCheques',
			'columns'=>array(
				array(
		            'header' => '',
		            'class' => 'CCheckBoxColumn',
		        ),
				array(
					'name'=>'numeroCheque',
					'header'=>'Numero Cheque',
					'value'=>'$data->numeroCheque',
					'htmlOptions'=>array('style'=>'text-align: right'),
				),
				array(
					'name'=>'bancoId',
					'header'=>'Banco',
					'value'=>'$data->banco->nombre',
				),
	            array(
	                'name' => 'librador_denominacion',
	                'header' => 'Librador',
	                'value' => '$data->librador->denominacion',
	            ),
				array(
					'name'=>'montoOrigen',
					'header'=>'Monto',
					'value'=>'Yii::app()->numberFormatter->format("#,##0.00",$data->montoOrigen)',
					'htmlOptions'=>array('style'=>'text-align: right'),
				),
				array(
					'name'=>'fechaPago',
					'header'=>'Fecha Pago',
					'value'=>'date("d/m/Y", strToTime($data->fechaPago))',
					'htmlOptions'=>array('style'=>'text-align: right'),
				),
				array(
					'name'=>'tipoCheque',
					'header'=>'Destino',
					'value'=>'$data->getTypeDescription("tipoCheque")',
				),
		            	array(
					'name'=>'operacionCheque',
					'header'=>'Endosante',
					'value'=>'$data->operacionCheque->cliente->razonSocial',
				),
				array(
					'name'=>'endosante',
					'header'=>'2do Endoso',
					'value'=>'$data->endosante',
				),
				array(
					'name'=>'estado',
					'header'=>'Estado',
					'value'=>'$data->getTypeDescription("estado")',
					'filter'=> Cheques::model()->getTypeOptions("estado"),  
				),
			),
		));
	?>
</div>

<?php $this->endWidget(); ?>