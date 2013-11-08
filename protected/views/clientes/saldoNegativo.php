<h1>Clientes con Saldo Negativo</h1>

<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => Clientes::model()->getSaldoNegativo(),
    'columns' => array(
        array(
            'name' => 'Codigo',
            'type' => 'raw',
            'value' => 'CHtml::encode($data["id"])'
        ),

        array(
            'name' => 'Cliente',
            'type' => 'raw',
            'value' => 'CHtml::encode($data["razonSocial"])'
        ),
        array(
            'name' => 'Saldo',
            'type' => 'raw',
            'value' => 'Utilities::MoneyFormat($data["saldoActual"])'
        ),
      
    ),
));
?>
