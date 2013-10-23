<?php

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('detalle-pesificaciones-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Historial de pesificaciones</h1>

<div class="search-form">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'detalle-pesificaciones-grid',
	'dataProvider'=>$model->search(),
    'filter'=>$model,
	
	'columns'=>array(
		 array(
            'header'=>'#',
            'value'=>'$this->grid->dataProvider->pagination->currentPage * $this->grid->dataProvider->pagination->pageSize + ($row+1)',
        ),
		array(
                'name' => 'pesificadorId',
                'header' => 'Pesificador',
                'value' => '$data->pesificacion->pesificador->denominacion',
                
            ),
		array(
                'name' => 'pesificadorId',
                'header' => 'Fecha',
                'value' => '$data->pesificacion->fecha',
                
            ),
		array(
                'name' => 'pesificacionId',
                'header' => 'Tasa',
                'value' => '$data->pesificacion->tasaPesificacion',
                
            ),
		array(
                'name' => 'chequeId',
                'header' => 'Num.Cheque',
                'value' => '$data->cheque->numeroCheque',   
        ),
        array(
                'name' => 'chequeId',
                'header' => 'Librador',
                'value' => '$data->cheque->librador->denominacion',   
        ),
        array(
                'name' => 'chequeId',
                'header' => 'Bco',
                'value' => '$data->cheque->banco->nombre',   
        ),
        array(
                'name' => 'chequeId',
                'header' => 'Monto Nominal',
                'value' => '$data->cheque->montoOrigen',   
        ),
		array(
                'name' => 'chequeId',
                'header' => 'Monto Gastos',
                'value' => '$data->cheque->montoOrigen*($data->pesificacion->tasaPesificacion/100)',   
        ),
        array(
                'name' => 'chequeId',
                'header' => 'Cliente Tomador',
                'value' => '$data->cheque->operacionCheque->cliente->razonSocial',   
        ),
        array(
                'name' => 'chequeId',
                'header' => 'Fecha Vto. Cheque',
               'value' => '$data->cheque->fechaPago',   
        ),
        
	),
)); ?>
