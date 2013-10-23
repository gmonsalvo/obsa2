<h1>Ranking de Clientes</h1>


<script>
function exportar(obj){
    if(obj.value=="Exportar Todo")
        var paginacion = 0;
    else
        var paginacion = 1;
    window.open("/clientes/exportarRanking?paginacion="+paginacion);
}
</script>
<input type="button" onclick="exportar(this)" value="Exportar Vista">
<input type="button" onclick="exportar(this)" value="Exportar Todo">
<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $arrayDataProvider,
    'filter'=>$filtersForm,
    'columns' => array(
        array(
            'header'=>CHtml::encode('Cliente'),
            'name' => 'razonSocial',
            'type' => 'raw',
            'value' => 'CHtml::encode($data["razonSocial"])'
        ),
        array(
            'header'=>CHtml::encode('Efectivo'),
            'name' => 'saldo',
            'type' => 'raw',
            'filter' => false,
            'value' => 'Utilities::MoneyFormat($data["saldo"])'
        ),
        array(
            'header'=>CHtml::encode('Cheques'),
            'name' => 'saldoColocaciones',
            'type' => 'raw',
            'filter' => false,
            'value' => 'Utilities::MoneyFormat($data["saldoColocaciones"])'
        ),
        array(
            'header'=>CHtml::encode('Total'),
            'name' => 'total',
            'type' => 'raw',
            'filter' => false,
            'value' => 'Utilities::MoneyFormat($data["total"])'
        ),
        array(
                'name' => 'porcentajeInversion',
                'type' => 'raw',
                'filter' => false,
                'header' => CHtml::encode('Porc. Disponibilidad'),
                'value' => '$data["porcentajeInversion"]',
            ),
        array(
            'header'=>CHtml::encode('%'),
            'name' => 'porcentaje',
            'type' => 'raw',
            'filter' => false,
            'value' => 'Utilities::truncateFloat($data["porcentaje"],2)'
        ),
    ),
));
?>
<div align="right">Total: <?php echo Utilities::MoneyFormat($sumaTotal); ?></div>