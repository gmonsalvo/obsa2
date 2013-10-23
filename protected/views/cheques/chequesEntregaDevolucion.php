<br />
<h4>Listado de cheques para <?php echo $accion ?> cliente <?php echo $razonSocial ?></h4>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'cheques-grid',
	'dataProvider'=>$dataProvider,
	'selectableRows' => -1,
	'selectionChanged'=>'habilitarSubmit',
	'columns'=>array(
	    array(
			'header'=>'Cheques seleccionados',
            'class'=>'CCheckBoxColumn',
            'id'=>'chequesSeleccionados',            
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
			'name'=>'tipoCheque',
			'header'=>'Tipo Cheque',
			'value'=>'$data->getTypeDescription("tipoCheque")',
		),
		// array(
		// 	'name'=>'estado',
		// 	'header'=>'Estado',
		// 	'value'=>'$data->getTypeDescription("estado")',
		// ),
	),
)); ?>
