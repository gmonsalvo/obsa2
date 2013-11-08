<h1>Ranking de Endosantes</h1>


<script>
function exportar(obj){
    if(obj.value=="Exportar Todo")
        var paginacion = 0;
    else
        var paginacion = 1;
    window.open("/clientes/exportarRankingEndosantes?paginacion="+paginacion);
}
</script>
<input type="button" onclick="exportar(this)" value="Exportar Vista">
<input type="button" onclick="exportar(this)" value="Exportar Todo">
<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'name' => 'razonSocial',
            'header' => 'Cliente',
            'value' => '$data->razonSocial'
        ),
        array(
            'header' => 'Monto Neto Cheques Comprados',
            'name' => 'montoChequesComprados',
            'value' => 'Utilities::MoneyFormat($data->montoChequesComprados)'
        ),
        array(
            'header' => 'Cantidad Cheques Comprados',
            'name' => 'cantidadChequesComprados',
            'value' => '$data->cantidadChequesComprados'
        ),
    ),
));
?>
<div align="right">Total:</div>