<?php
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
<script>
$(document).ready(function() {
    $('#clientes-grid tbody tr').live('dblclick', function() {
        if($.fn.yiiGridView.getSelection("cheques-grid")==''){
            alert("Debe seleccionar un cheque de la lista para habilitar la colocacion");
        }else {
            var id = $.fn.yiiGridView.getKey(
                'clientes-grid',
                $(this).prevAll().length
            );

            $.post("<?php echo CHtml::normalizeUrl(array('clientes/getCliente', 'render' => false))?>",{id: id},function(data){
                            var datos=jQuery.parseJSON(data);
                            $( "#dialog-form" ).dialog( "open" );
                            $("#nombreinversor").text("Inversor: "+datos.cliente.razonSocial);
                            $("#inversor").val(datos.cliente.razonSocial);
                            $("#idCliente").val(datos.cliente.id);
                            $("#montodisponible").val(datos.saldo);
                            // $("#tasa").val(datos.cliente.tasaInversor);
                            if(parseFloat($("#montoPorColocar").val())>parseFloat($("#montodisponible").val()))
                                $("#montoValorActual").val($("#montodisponible").val());
                            else
                                $("#montoValorActual").val($("#montoPorColocar").val());
                            calcularInversaValorActual(true);
                            //$("#monto").focus();

            });
        }
    });
});
    function calcularValorActual() {
        var params = 
        {
            idCheque:$.fn.yiiGridView.getSelection("cheques-grid"), 
            montoColocado: $( "#monto" ).val(), 
            tasa: $( "#Colocaciones_tasa" ).val(), 
            clearing: $("#Colocaciones_clearing").val()
        }
        $.ajax({
            type: 'POST',
            url: "<?php echo CController::createUrl('colocaciones/calculoValorActual') ?>",
            data: params,
            dataType: 'Text',
            success:function(data){
                $("#montoValorActual").val(data);      
            }
        });
    }

    function calcularInversaValorActual(async) {
        var params = 
        {
            idCheque:$.fn.yiiGridView.getSelection("cheques-grid"), 
            montoColocado: $( "#montoValorActual" ).val(), 
            tasa: $( "#Colocaciones_tasa" ).val(), 
            clearing: $("#Colocaciones_clearing").val()
        }
        $.ajax({
            type: 'POST',
            url: "<?php echo CController::createUrl('colocaciones/calculoInversaValorActual') ?>",
            data: params,
            async: async,
            dataType: 'Text',
            success:function(data){
                $("#monto").val(data);
                //$("#montoPorColocar").val(data);        
            }
        });
    }

    function MoneyFormat(nStr)
    {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? ',' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + '.' + '$2');
        }
        return '$ '+x1 + x2;
    }

    function Unformat(nStr)
    {
        nStr += '';
        x = nStr.split('$ ');
        y = x[1];
        //return y;
        //alert(y);
        y = y.split('.');
        var z='';
        for(var i=0;i<y.length;i++)
            z=z+y[i];
        x = z.split(',');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        return x1 + x2;

    }

    function Limpiar()
    {
        $("#colocaciones tbody").empty();
        $('#cheques-colocados').css("display","none");
        //$.fn.yiiGridView.updateGrid('clientes-grid');
        //$.fn.yiiGridView.update('clientes-grid'); //hago un update para deseleccionar
        $("#submitBoton").attr('disabled','disabled');
    }

    function MostrarCheque(){

        if($.fn.yiiGridView.getSelection("cheques-grid")!=""){

            $("#Colocaciones_chequeId").val($.fn.yiiGridView.getSelection("cheques-grid"));
            $("#montoPorColocar").removeAttr('disabled');
            $.ajax({
                type: 'POST',
                url: "<?php echo CController::createUrl('cheques/viewCheque') ?>",
                data:{'id':$.fn.yiiGridView.getSelection("cheques-grid"), 'tasa': $("#Colocaciones_tasa").val(), 'clearing': $("#Colocaciones_clearing").val()},
                dataType: 'Text',
                success:function(data){
                    var datos=jQuery.parseJSON(data);
                    //var datos=data.split(";");
                    //$('#viewCheque').html(datos.detalleChequeVista);
                    //$("#montoPorColocar").val(datos.montoPorColocar);
                    $("#montoPorColocar").val(datos.valorActual);
                    $("#diasColocados").val(datos.diasColocados);
                    $("#Colocaciones_diasColocados").val(datos.diasColocados);
                    //$("#clearing").val(0); //$("#clearing").val(datos.clearing);

                    //$("#clearing_viejo").val(0); //   $("#clearing_viejo").val(datos.clearing);
                    Limpiar(); //borro la tabla y selecciones que haya
                }
            });
        }else{
            //$('#viewCheque').html("");
            $("#montoPorColocar").val("");
            $("#montoPorColocar").attr('disabled','disabled');
            Limpiar();
        }

    }

    function Create(){
        var error=0;
        if($('#cheques-colocados').css("display")!="none"){
            $("#colocaciones tbody tr").each(function() {
                // this represents the row
                //            $("td > *", this).each(function() { // nodeName attribute represents the tag - "INPUT" if element is <input> // use the type attribute to find out exactly what type // of input element it is - text, password, button, submit, etc.. if(this.nodeName == "INPUT") { console.log($(this).attr("type")); } }); });
                //                alert(this);
                //                });  .parent().children().eq(2)
                if($( "#idCliente" ).val()==$(this).children().eq(0).text()){
                    alert("El inversor " + $( "#inversor" ).val() + " ya fue ingresado. Eliminelo de la tabla y vuelva a cargarlo");
                    error=1;
                    return 0;
                }

            });
        }
        //el inversor ya fue colocado
        if(error)
            return 0;
        if($( "#monto" ).val()==''){
            alert('Debe ingresar un monto para la colocacion');
            return 0;
        }
        // if($( "#tasa" ).val()==''){
        //     alert('Debe ingresar una tasa para la colocacion');
        //     return 0;
        // }

        // if(isNaN($("#tasa").val())){
        //     alert('La tasa debe ser un numero');
        //     return 0;
        // }
        // if(parseFloat($( "#tasa" ).val())<=0){
        //     alert('La tasa debe ser mayor a 0');
        //     return 0;
        // }
        if(isNaN($("#montoValorActual").val())) {
            alert('El monto debe ser un numero');
            return 0;
        }
        if(parseFloat($( "#montoValorActual" ).val())<=0){
            alert("El monto debe ser superior a 0");
            return 0;
        }
        if(parseFloat($( "#montoValorActual" ).val())>parseFloat($('#montodisponible').val())){
            alert("El monto que esta asignando es mayor al monto disponible para colocar. Por favor modifique el monto a asignar");
            return 0;
        }

        if(parseFloat($( "#montoValorActual" ).val())>parseFloat($('#montoPorColocar').val())){
            alert("El monto que esta asignando es mayor al monto por colocar. Por favor modifique el monto a asignar");
            return 0;
        }

        var params = 
        {
            idCheque:$.fn.yiiGridView.getSelection("cheques-grid"), 
            montoColocado: $( "#monto" ).val(), 
            tasa: $( "#Colocaciones_tasa" ).val(), 
            clearing: $("#Colocaciones_clearing").val()
        };
        calcularInversaValorActual(false);
        $('#cheques-colocados').css("display","block");
        $( '#colocaciones tbody' ).append( '<tr>' +
            '<td>' + $( "#idCliente" ).val() + '</td>' +
            '<td>' + $( "#inversor" ).val() + '</td>' +
            '<td>'+ MoneyFormat($( "#montoValorActual" ).val()) + '</td>' +
            '<td>'+ MoneyFormat($( "#monto" ).val()) + '</td>' +
            '<td>' + $( "#Colocaciones_tasa" ).val() + '</td>' +
            '<td onclick="Eliminar(this)"><span class="link">borrar</span></td>'+
            '</tr>' );
        var monto=parseFloat($('#montoPorColocar').val())-parseFloat($( "#montoValorActual" ).val());
        $( "#Colocaciones_tasa" ).attr("disabled",true);
        $( "#Colocaciones_clearing" ).attr("disabled",true);
        $('#montoPorColocar').val(parseFloat(monto));
        if($('#montoPorColocar').val()==0)
            $("#submitBoton").removeAttr('disabled');
        CrearDetallesColocaciones();
        // $.ajax({
        //     type: 'POST',
        //     url: "<?php echo CController::createUrl('colocaciones/calculoInversaValorActual') ?>",
        //     data: params,
        //     dataType: 'Text',
        //     success:function(data){

        //         $('#cheques-colocados').css("display","block");
        //         $( '#colocaciones tbody' ).append( '<tr>' +
        //             '<td>' + $( "#idCliente" ).val() + '</td>' +
        //             '<td>' + $( "#inversor" ).val() + '</td>' +
        //             '<td>'+ MoneyFormat(data) + '</td>' +
        //             '<td>'+ MoneyFormat($( "#monto" ).val()) + '</td>' +
        //             '<td>' + $( "#Colocaciones_tasa" ).val() + '</td>' +
        //             '<td onclick="Eliminar(this)"><span class="link">borrar</span></td>'+
        //             '</tr>' );
        //         var monto=parseFloat($('#montoPorColocar').val())-parseFloat($( "#montoValorActual" ).val());
        //         $( "#Colocaciones_tasa" ).attr("disabled",true);
        //         $( "#Colocaciones_clearing" ).attr("disabled",true);
        //         $('#montoPorColocar').val(parseFloat(monto));
        //         if($('#montoPorColocar').val()==0)
        //             $("#submitBoton").removeAttr('disabled');
        //         CrearDetallesColocaciones();

        //     }
        // });
        $( this ).dialog( 'close' );
    }
    function Close(){
        $( this ).dialog( 'close' );
    }
    function Eliminar(obj){
        if(confirm("Desea eliminar la fila")){
            var montoColocado=Unformat($(obj).parent().children().eq(2).text()); //monto colocado
            var monto=parseFloat($('#montoPorColocar').val())+parseFloat(montoColocado);
            $('#montoPorColocar').val(parseFloat(monto));
            $("#submitBoton").attr('disabled','disabled');
            $(obj).parent("tr").remove();
            if($('#colocaciones >tbody >tr').length == 0) {
                $( "#Colocaciones_tasa" ).removeAttr("disabled");
                $( "#Colocaciones_clearing" ).removeAttr("disabled");
                $('#cheques-colocados').css("display","none");
            }
        }

    }

    function CrearDetallesColocaciones()
    {
        var detalleColocaciones = $('#colocaciones tr').map(function() {
            return $(this).find('td').map(function() {
                if($(this).html().indexOf("$")!=-1)
                    return Unformat($(this).html());
                else
                    return $(this).html();
            }).get();
        }).get();
        $('#detallesColocaciones').val(detalleColocaciones);
    }

    function calcularDiasColocados(){
        var diasColocados=parseInt($("#diasColocados").val());
        // diasColocados=diasColocados-parseInt($("#clearing_viejo").val())+parseInt($("#clearing").val());
        // $("#clearing_viejo").val($("#clearing").val());
        // $("#diasColocados").val(diasColocados);
        // $("#Colocaciones_diasColocados").val(diasColocados);
        //$("#Colocaciones_clearing").val($("#clearing").val());
    }


</script>
<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'colocaciones-form',
        'enableAjaxValidation' => false,
            ));
    ?>
    <?php echo $form->errorSummary($model); ?>

    <table>
        <tr>
            <td><?php echo $form->labelEx($model, 'fecha'); ?></td>
            <td><?php echo CHtml::label('Clearing', 'Colocaciones_clearing'); ?></td>
            <td><?php echo CHtml::label('Tasa', 'Colocaciones_tasa'); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->textField($model, 'fecha', array('readonly' => 'readonly', 'value' => date('d/m/Y'))); ?></td>
            <td><?php echo CHtml::textField('clearing', 0, array("id"=>"Colocaciones_clearing", "onblur" =>"MostrarCheque()")); ?></td>
            <td><?php echo CHtml::textField('tasa', 24, array("id"=>"Colocaciones_tasa", "onblur" =>"MostrarCheque()")); ?></td>
        </tr>
    </table>
        
    <?php echo $form->hiddenField($model, 'chequeId'); ?>
    <?php echo $form->hiddenField($model, 'diasColocados',array('value' => 0 )); ?>
    <?php echo CHtml::hiddenField('detallesColocaciones', ''); ?>
    <?php echo $form->hiddenField($model, 'montoTotal', array('value' => 0)); ?>

    <?php
    $this->beginWidget('zii.widgets.CPortlet', array(
        'id' => 'portletCheques',
        'title' => '',
    ));
    echo "<b>Cheques en Cartera</b>";
    $this->endWidget("portletCheques");
    ?>


    <?php
    //echo $cheques->searchChequesByEstado(Cheques::TYPE_EN_CARTERA_SIN_COLOCAR);
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'cheques-grid',
        'dataProvider' => $cheques->searchChequesByEstado(Cheques::TYPE_EN_CARTERA_SIN_COLOCAR), //busco del tipo cheques en cartera a colocar
        'filter' => $cheques,
        'selectionChanged' => 'MostrarCheque',
        'columns' => array(
            array(
                'name' => 'fechaPago',
                'header' => 'Venc.',
                'value' => 'Utilities::ViewDateFormat($data->fechaPago)',
            ),
            array(
                'name' => 'montoOrigen',
                'header' => 'Importe',
                'value' => 'Utilities::MoneyFormat($data->montoOrigen)',
            ),
            array(
                'name' => 'librador_denominacion',
                'header' => 'Librador',
                'value' => '$data->librador->denominacion',
            ),
            array(
                'name' => 'endosante',
                'header' => 'Endosante',
                'value' => '$data->endosante',
            ),
            array(
                'name' => 'tipoCheque',
                'header' => 'Destino',
                'type' => "raw",
                'value' => '$data->getTypeDescription("tipoCheque")',
            ),
        ),
        'htmlOptions' => array(
            'class' => 'grid-view mgrid_table',
        ),
    ));
    ?>
    <!-- <div id='viewCheque'></div> -->
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
        'dataProvider' => $clientes->searchInversoresConSaldoCtaCte(),
        'filter' => $clientes,
        // 'selectionChanged' => 'HabilitarColocacion',
        'columns' => array(
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

// $this->widget('zii.widgets.grid.CGridView', array(
//     'dataProvider' => $clientes->searchInversoresParaColocacion2(),
//     'selectionChanged' => 'HabilitarColocacion',
//     'columns' => array(
//         array(
//             'name' => 'Razon Social',
//             'type' => 'raw',
//             'value' => 'CHtml::encode($data["razonSocial"])'
//         ),
//         array(
//             'name' => 'Efectivo',
//             'type' => 'raw',
//             'value' => 'Utilities::MoneyFormat($data["saldo"])'
//         ),
//         array(
//             'name' => '% Inversion',
//             'type' => 'raw',
//             'value' => '$data["porcentaje"]'
//         ),
//         array(
//             'name' => 'Tasa Inversor',
//             'type' => 'raw',
//             'value' => '$data["tasaInversor"]'
//         ),
//         array(
//             'name' => 'Operador',
//             'type' => 'raw',
//             'value' => '$data["operador"]'
//         ),
//     ),
// ));
    ?>

    <style>
        label, input { display:block; }
        input.text { margin-bottom:12px; width:95%; padding: .4em; }
        fieldset { padding:0; border:0; margin-top:25px; }
        h1 { font-size: 1.2em; margin: .6em 0; }
        div#users-contain { width: 350px; margin: 20px 0; }
        div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
        div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
        .ui-dialog .ui-state-error { padding: .3em; }
        .validateTips { border: 1px solid transparent; padding: 0.3em; }
        .mgrid_table tbody:hover
        {
            cursor: pointer;
        }
        .link{
            color:blue;
            text-decoration: underline;
        }
        .link:hover{
            text-decoration: underline;
            color:blue;
            cursor:pointer}
        </style>
        <br>
        <?php
        $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
            'id' => 'dialog-form',
            'options' => array(
                'title' => 'Colocar inversor',
                'autoOpen' => false,
                'modal' => 'true',
                'buttons' => array(
                    'Asignar colocacion' => "js:Create",
                    'Cancelar' => "js:Close",
                ),
            ),
            'htmlOptions' => array('style' => 'font-size: 62.5%;height:476.133px'),
        ));
        ?>
        <div title="Colocacion Inversor" style="font-size: 120%;">
        <p class="validateTips"><b>Todos los campos son requeridos.</b></p>

        <fieldset>
            <label for="inversor" id="nombreinversor"></label>
            <input type="hidden" name="inversor" id="inversor" class="text ui-widget-content ui-corner-all" />
            <input type="hidden" name="idCliente" id="idCliente" class="text ui-widget-content ui-corner-all" />
            <input type="hidden" name="clearing_viejo" id="clearing_viejo" class="text ui-widget-content ui-corner-all" />
            Monto Disponible:
            <input type="text" name="montodisponible" id="montodisponible" value="" class="text ui-widget-content ui-corner-all" readonly="readonly" disabled="disabled" />
<!--             <label for="clearing">Clearing:</label>
            <input type="text" name="clearing" id="clearing" value="" onblur="calcularDiasColocados()" class="text ui-widget-content ui-corner-all" /> -->
            <label for="diasColocados">Dias colocados:</label>
            <input type="text" name="diasColocados" id="diasColocados" value="" class="text ui-widget-content ui-corner-all" readonly="readonly" disabled="disabled" />
            <label for="montoValorActual">Monto valor actual</label>
            <input type="text" name="montoValorActual" id="montoValorActual" value="" class="text ui-widget-content ui-corner-all" onchange="calcularInversaValorActual(true)" />
            <label for="monto">Monto valor nominal</label>
            <input type="text" name="monto" id="monto" readonly="readonly" value="" class="text ui-widget-content ui-corner-all" />

<!--             <label for="tasa">Tasa</label>
            <input type="text" name="tasa" id="tasa" value="" class="text ui-widget-content ui-corner-all" /> -->
        </fieldset>
    </div>
    <?php $this->endWidget('zii.widgets.jui.CJuiDialog'); ?>
    <div id="cheques-colocados" class="ui-widget" style="display:none">
        <?php
        $this->beginWidget('zii.widgets.CPortlet', array(
            'title' => '',
        ));
        echo "<b>Colocaciones</b>";
        $this->endWidget("dialog-form");
        ?>
        <table id="colocaciones" class="ui-widget ui-widget-content">
            <thead>
                <tr class="ui-widget-header ">
                    <th>Id Inversor</th>
                    <th>Razon social</th>
                    <th>Monto valor actual</th>
                    <th>Monto valor nominal</th>
                    <th>Tasa</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <div class="row">
        <?php echo CHtml::label('Monto por colocar', 'label1'); ?>
        <?php echo CHtml::textField('montoPorColocar', '', array('id' => 'montoPorColocar', 'size' => 15, 'maxlength' => 15, 'readonly' => 'readonly', 'disabled' => 'disabled')); ?>
        <?php //echo $form->error($model,'montoTotal');  ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Cerrar colocacion', array('id' => 'submitBoton', 'disabled' => 'disabled', 'onclick' => 'CrearDetallesColocaciones()')); ?>
    </div>

    <?php $this->endWidget("colocaciones-form"); ?>

</div><!-- form -->