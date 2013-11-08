<?php
if (isset($_POST['operadorId']) && isset($_POST['clienteId']) && isset($_POST['fecha'])) {
    $operadorId = $_POST['operadorId'];
    $clienteId = $_POST['clienteId'];
    $fecha = $_POST['fecha'];
} else {
    if (isset($_GET['operadorId']) && isset($_GET['clienteId']) && isset($_GET['fecha'])) {
        $operadorId = $_GET['operadorId'];
        $clienteId = $_GET['clienteId'];
        $fecha = $_GET['fecha'];
    } else {
        $operadorId = '';
        $clienteId = '';
        $fecha = Date('d/m/Y');
    }
}
?>
    <?php Yii::app()->clientScript->registerScript('setFocus', '$("#clienteId_lookup").focus();') ;?>
    <?php Yii::app()->clientScript->registerScript('lostFocus', 
    '$("#PresupuestosCheques_pesificacion").live("focusout",function(){
        $("#PresupuestosCheques_numeroCheque").focus();
    });'
); ?>
    <?php Yii::app()->clientScript->registerScript('cambiarLayout', '

    $("#content").parent().parent().find(".span5 last").remove();
    $("#content").parent().parent().find(".span-22").removeClass("span-22").addClass("span-24");
    $("#content").width("950px");
    ') ;?>
<script>
    //$("#fechaOperacion").val($("#PresupuestoOperacionesCheques_fecha").val());

    function LimpiarFormulario()
    {
        $( "#PresupuestosCheques_numeroCheque" ).val('');
        $( "#PresupuestosCheques_libradorId" ).val('');
        $( "#PresupuestosCheques_montoOrigen" ).val('');
        $( "#PresupuestosCheques_fechaPago" ).val('');
        $( "#PresupuestosCheques_bancoId" ).val(0);
        $( "#PresupuestosCheques_tipoCheque" ).val(0);
        $( "#PresupuestosCheques_endosante" ).val('');
        //$( "#PresupuestosCheques_tasaDescuento" ).val('<?php echo Yii::app()->user->model->sucursal->tasaDescuentoGeneral ?>');
        //$( "#PresupuestosCheques_tasaDescuento" ).removeAttr("disabled");
        $( "#PresupuestosCheques_clearing" ).val('<?php echo Yii::app()->user->model->sucursal->diasClearing ?>');
        $( "#PresupuestosCheques_clearing" ).removeAttr("disabled");
        //$( "#PresupuestosCheques_pesificacion" ).val('<?php echo Yii::app()->user->model->sucursal->tasaPesificacion ?>');
        $( "#PresupuestosCheques_montoNeto" ).val(0);
        $( "#PresupuestosCheques_tieneNota" ).attr('checked',false);
        $("#libradorId_lookup").val("");
        $("#libradorId_save").val("");
        $("#PresupuestosCheques_fechaPago").focus();
        getDatosCliente();
        $("#montoOrigen_text").val("");
        var tdtabla=$(".formulario").find(".resultado").find(".tablaresultado");
        tdtabla.html("");
    }

    function EliminarCheque(url)
    {
        var valor=url.split("/");
        id=valor[valor.length-1];
        $.fn.yiiGridView.update('tmp-cheques-grid', {
            type :'GET',
            url  : url,
            success:function(data,status) {
                $.fn.yiiGridView.update('tmp-cheques-grid');
                var datos=jQuery.parseJSON(data);
                $("#PresupuestoOperacionesCheques_montoNetoTotal").val(datos.montoNetoTotal);
                $("#montoNominalTotal").val(datos.montoNominalTotal);
                $("#totalIntereses").val(datos.totalIntereses);
                $("#totalPesificacion").val(datos.totalPesificacion);
            }
        });

    }

    function VerificarMascara(obj)
    {
        var valor=$(obj).val().replace( "_", "0");
        valor=valor.replace( "_", "0");
        $(obj).val(valor);
    }

    function getDatosCliente(){

        if($("#PresupuestoOperacionesCheques_clienteId").val()!=""){
            $.ajax({
                type: 'POST',
                url: "<?php echo CController::createUrl('clientes/getCliente') ?>",
                data:{'id':$("#PresupuestoOperacionesCheques_clienteId").val()},
                dataType: 'text',
                success:function(data){
                    var datos=jQuery.parseJSON(data);
                    var cliente=datos.cliente;
                    if(cliente.tasaTomador!="")
                        $("#PresupuestosCheques_tasaDescuento").val(parseFloat(cliente.tasaTomador));
                    if(cliente.tasaPesificacionTomador!="")
                        $("#PresupuestosCheques_pesificacion").val(parseFloat(cliente.tasaPesificacionTomador));
                }
            });
        }
    }

    function esChequeCorriente(){
        var fecOp = new Date($("#PresupuestoOperacionesCheques_fecha").datepicker("getDate"));
        var today = new Date();
        var fecPago = new Date($( "#PresupuestosCheques_fechaPago" ).datepicker("getDate"));
        var fecMargenCorriente = new Date();
        fecMargenCorriente.setDate(fecOp.getDate() - 31);
        var fecLimiteAFuturo = new Date();
        fecLimiteAFuturo.setDate(today.getDate() + 365);
        if((fecPago > fecMargenCorriente) && (fecPago <= fecOp)) {  
            $("#PresupuestosCheques_tasaDescuento").attr("disabled","disabled");
            $("#PresupuestosCheques_clearing").attr("disabled","disabled");
            $("#PresupuestosCheques_pesificacion").focus();
        } else {
            //fecha Invalida
            if(fecPago < fecMargenCorriente) {
                alert("El cheque ingresado ya expiro");
            } else {
                if(fecPago > fecLimiteAFuturo)
                    alert("La fecha de pago no puede ser mas de un año en el futuro");
                else {
                    $("#PresupuestosCheques_tasaDescuento").removeAttr("disabled");
                    $("#PresupuestosCheques_clearing").removeAttr("disabled");
                    $("#PresupuestosCheques_numeroCheque").focus();
                }
            }
        }
            // $.ajax({
            //     type: 'POST',
            //     url: "<?php echo CController::createUrl('tmpCheques/esChequeCorriente') ?>",
            //     data:{'fechaPago':$( "#PresupuestosCheques_fechaPago" ).val(), 'fechaOperacion': $("#PresupuestoOperacionesCheques_fecha").val()},
            //     dataType: 'text',
            //     success:function(data){
            //         var datos=jQuery.parseJSON(data);
            //         if(datos.esCorriente==true){
            //             $("#PresupuestosCheques_tasaDescuento").attr("disabled","disabled");
            //             $("#PresupuestosCheques_clearing").attr("disabled","disabled");
            //         } else {
            //             $("#PresupuestosCheques_tasaDescuento").removeAttr("disabled");
            //             $("#PresupuestosCheques_clearing").removeAttr("disabled");
            //         }

            //     }
            // });
    }

    function esFechaValida(txtDate)
    {
          var currVal = txtDate.val();
          if(currVal == '')
            return false;

          //Declare Regex
          var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
          var dtArray = currVal.match(rxDatePattern); // is format OK?

          if (dtArray == null)
             return false;

          //Checks for mm/dd/yyyy format.
          dtMonth = dtArray[3];
          dtDay= dtArray[1];
          dtYear = dtArray[5];

          if (dtMonth < 1 || dtMonth > 12)
              return false;
          else if (dtDay < 1 || dtDay> 31)
              return false;
          else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
              return false;
          else if (dtMonth == 2)
          {
             var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
             if (dtDay> 29 || (dtDay ==29 && !isleap))
                  return false;
          }
          if(dtMonth<10 && dtMonth.indexOf("0")==-1)
            dtMonth="0"+dtMonth;
        if(dtDay<10 && dtDay.indexOf("0")==-1)
            dtDay="0"+dtDay;
        txtDate.val(dtDay+"/"+dtMonth+"/"+dtYear);
          return true;
    }

</script>

<style>
    .tabla th, td, caption {
        padding: 0px 5px 0px 0px;
    }

    select:focus { border:1px solid black;}
</style>
<?php
if (count($presupuestosCheque->getErrors()) > 0) {
    $error = $presupuestosCheque->getErrors();
    echo var_dump($error);
}
$model->init();
?>

<div class="form">

    <p class="note">Campos con <span class="required">*</span> son requeridos.</p>

    <table>
        <tr>
            <td><?php echo CHtml::label("Fecha", 'fecha'); ?></td>
            <td><?php
    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
        // you must specify name or model/attribute
        'model' => $model,
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
            'onChange' => 'js:$("#PresupuestoOperacionesCheques_fecha").focus(); $("#fecha").val($("#OperacionesCheques_fecha").val()',
        )
            )
    );
    ?>
            </td>
            <td>
    <?php echo CHtml::label("Cliente", 'clienteId'); ?></td>
            <td><?php
                $this->widget('CustomEJuiAutoCompleteFkField', array(
                    'model' => $model,
                    'attribute' => 'clienteId', //the FK field (from CJuiInputWidget)
                    // controller method to return the autoComplete data (from CJuiAutoComplete)
                    'sourceUrl' => Yii::app()->createUrl('/clientes/buscarRazonSocial', array("tipo[0]" => Clientes::TYPE_TOMADOR, "tipo[1]" => Clientes::TYPE_TOMADOR_E_INVERSOR)),
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
                    'select'=>"getDatosCliente();",
                    'options' => array(
                        // number of characters that must be typed before
                        // autoCompleter returns a value, defaults to 2
                        'minLength' => 1,
                    ),
                    'htmlOptions' => array(
                        'tabindex'=>1),
                ));
    ?>
            </td>
        </tr>
    </table>
</div><!-- form -->
<div id="result"></div>
<div id="chequestemporales">
    <?php
    Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('tmp-cheques-grid', {
		data: $(this).serialize()
	});
	return false;
});
");


    $this->beginWidget('zii.widgets.CPortlet', array(
        'title' => '',
    ));
    echo "<b>Cheques de la Operacion</b>";
    $this->endWidget();
    ?>


    <?php //echo var_dump($presupuestosCheuqes->getErrors()); ?>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'tmp-cheques-grid',
        'dataProvider' => $presupuestosCheque->searchByUserName(),
        'columns' => array(
            array(
                'name' => 'bancoId',
                'header' => 'Banco',
                'value' => '$data->banco->nombre',
            ),
            'numeroCheque',
            array(
                'name' => 'fechaPago',
                'header' => 'Fecha Vencim.',
                'value' => '$data->fechaPago',
            ),
            array(
                'name' => 'diasAlVenc',
                'header' => 'Dias al Venc.',
                'value' => '$data->diasAlVenc',
            ),
            array(
                'name' => 'libradorId',
                'header' => 'Librador',
                'value' => '$data->librador->denominacion',
            ),
            array(
                'name' => 'montoOrigen',
                'header' => 'Valor Nominal',
                'value' => 'Utilities::MoneyFormat($data->montoOrigen)',
            ),
            array(
                'name' => 'tasaDescuento',
                'header' => 'Gastos por Intereses',
                'value' => 'Utilities::MoneyFormat($data->descuentoTasa)',
            ),
            array(
                'name' => 'pesificacion',
                'header' => 'Gastos por Pesific.',
                'value' => 'Utilities::MoneyFormat($data->descuentoPesific)',
            ),
            array(
                'name' => 'montoNeto',
                'header' => 'Neto',
                'value' => 'Utilities::MoneyFormat($data->montoNeto)',
            ),
            array(
                'class' => 'CButtonColumn',
                'header' => 'Opciones',
                'template' => '{Eliminar}',
                'buttons' => array(
                    'Eliminar' => array(
                        'label' => 'Eliminar',
                        'url' => 'Yii::app()->createUrl("/presupuestosCheques/deleteCheque", array("id" => $data->id))',
                        'options' => array(// this is the 'html' array but we specify the 'ajax' element
                            'class' => 'cssGridButton',
                        ),
                        'click' => 'js:function() {
				if(confirm("Esta seguro que desea eliminar el cheque seleccionado?"))
					EliminarCheque( $(this).attr("href") ); return false;}'
                )),
            ),
        )
    ));
    ?>

</div>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'tmp-cheques-form',
        'enableAjaxValidation' => true,
            //'action'=>Yii::app()->createUrl("/PresupuestosCheques/addnew"),
            ));
    ?>

    <p class="note">Campos con <span class="required">*</span> son obligatorios.</p>

    <div id="erroresCheque"><?php echo $form->errorSummary($presupuestosCheque); ?></div>

    <table class="formulario tabla">
        <tr>
            <td><?php echo $form->labelEx($presupuestosCheque, 'fechaPago'); ?></td>
            <td><?php echo $form->labelEx($presupuestosCheque, 'tasaDescuento'); ?></td>
            <td><?php echo $form->labelEx($presupuestosCheque, 'clearing'); ?></td>
            <td><?php echo $form->labelEx($presupuestosCheque, 'pesificacion'); ?></td>
        </tr>
        <tr>
            <td>
                <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                    // you must specify name or model/attribute
                    'model' => $presupuestosCheque,
                    'attribute' => 'fechaPago',
                    'value' => $presupuestosCheque->fechaPago,
                    'language' => 'es',
                    'options' => array(
                        // how to change the input format? see http://docs.jquery.com/UI/Datepicker/formatDate
                        'dateFormat' => 'dd/mm/yy',
                        'defaultDate' => $presupuestosCheque->fechaPago,
                        'monthNames' => array('Enero,Febrero,Marzo,Abril,Mayo,Junio,Julio,Agosto,Septiembre,Octubre,Noviembre,Diciembre'),
                        'monthNamesShort' => array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"),
                        'dayNames' => array('Domingo,Lunes,Martes,Miercoles,Jueves,Viernes,Sabado'),
                        'dayNamesMin' => array('Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'),
                        'changeMonth' => 'true',
                        'changeYear' => 'true',
                        // shows the button panel under the calendar (buttons like "today" and "done")
                        'showButtonPanel' => 'true',
                        // this is useful to allow only valid chars in the input field, according to dateFormat
                        'constrainInput' => 'false',
                        // speed at which the datepicker appears, time in ms or "slow", "normal" or "fast"
                        'duration' => 'fast',
                        // animation effect, see http://docs.jquery.com/UI/Effects
                        'showAnim' => 'fold',
                    ),
                    // optional: html options will affect the input element, not the datepicker widget itself
                    'htmlOptions' => array(
                        'style' => 'height:20px;',
                        'onChange' => 'js:if(esFechaValida($( "#PresupuestosCheques_fechaPago" ))) esChequeCorriente(); else { alert("Fecha Invalida"); $( "#PresupuestosCheques_fechaPago" ).focus(); }',
                        'tabindex'=>2,
                    )
                ));
                ?>
            </td>
            <td><?php
                $this->widget('CMaskedTextField', array(
                    'model' => $presupuestosCheque,
                    'attribute' => 'tasaDescuento',
                    'name' => 'PresupuestosCheques_tasaDescuento',
                    'mask' => '9.99',
                    'htmlOptions' => array(
                        'onblur' => 'js:VerificarMascara(this);',
                        'style' => 'width:80px;',
                        'value' => Yii::app()->user->model->sucursal->tasaDescuentoGeneral,

                    ),
                ));
                ?>
            </td>
            <td><?php echo $form->textField($presupuestosCheque, 'clearing', array('size' => 10, 'value' => Yii::app()->user->model->sucursal->diasClearing)); ?></td>
            <td>
                <?php
                $this->widget('CMaskedTextField', array(
                    'model' => $presupuestosCheque,
                    'attribute' => 'pesificacion',
                    'name' => 'PresupuestosCheques_pesificacion',
                    'mask' => '9.99',
                    'htmlOptions' => array(
                        'onblur' => 'js:VerificarMascara(this);',
                        'style' => 'width:80px;',
                        'value' => Yii::app()->user->model->sucursal->tasaPesificacion,
                    ),
                ));
                ?>
            </td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($presupuestosCheque, 'numeroCheque'); ?></td>
            <td><?php echo $form->labelEx($presupuestosCheque, 'libradorId'); ?></td>
            <td colspan="2"><?php echo $form->labelEx($presupuestosCheque, 'bancoId'); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->textField($presupuestosCheque, 'numeroCheque', array('size' => 20, 'maxlength' => 45,'tabindex'=>3)); ?></td>
            <td>
                <?php

            $this->widget('EJuiAutoCompleteFkField', array(
                'model' => $presupuestosCheque,
                'attribute' => 'libradorId', //the FK field (from CJuiInputWidget)
                'sourceUrl' => Yii::app()->createUrl('/libradores/buscarLibradores'),
                'showFKField' => false,
                'FKFieldSize' => 15,
                'relName' => 'librador', // the relation name defined above
                'displayAttr' => 'denominacion', // attribute or pseudo-attribute to display
                'autoCompleteLength' => 40,
                'options' => array(
                    'minLength' => 1,
                ),
                'htmlOptions' => array(
                    'tabindex'=>4)
            )); ?>

                <?php //echo $form->dropDownList($presupuestosCheque, 'libradorId', CHtml::listData(Libradores::model()->findAll(), 'id', 'denominacion'), array('prompt' => 'Seleccione un Librador','tabindex'=>4,'class'=>'testClass')); ?></td>
            <td colspan="2"><?php echo $form->dropDownList($presupuestosCheque, 'bancoId', CHtml::listData(Bancos::model()->findAll(), 'id', 'nombre'), array('prompt' => 'Seleccione un Banco','tabindex'=>5)); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($presupuestosCheque, 'montoOrigen'); ?></td>
            <td><?php echo $form->labelEx($presupuestosCheque, 'tipoCheque'); ?></td>
            <td colspan="2"><?php echo $form->labelEx($presupuestosCheque, 'endosante'); ?></td>

        </tr>
        <tr>
            <td>
                <?php $this->widget("FormatCurrency",
                                array(
                                    "model" => $presupuestosCheque,
                                    "attribute" => "montoOrigen",
                                    "htmlOptions" => array("tabindex"=>6)
                                    ));
                ?>
                <?php //echo $form->textField($presupuestosCheque, 'montoOrigen', array('size' => 20, 'maxlength' => 17,'tabindex'=>6)); ?></td>
            <td><?php echo $form->dropDownList($presupuestosCheque, 'tipoCheque', $presupuestosCheque->getTypeOptions('tipoCheque'), array('prompt' => 'Seleccione un Tipo','tabindex'=>7)); ?></td>
            <td colspan="2"><?php echo $form->textField($presupuestosCheque, 'endosante', array('size' => 35, 'maxlength' => 100,'tabindex'=>8)); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->labelEx($presupuestosCheque, 'tieneNota'); ?>
<?php echo $form->checkBox($presupuestosCheque, 'tieneNota') ?>
            </td>
            <td colspan="3"></td>
        </tr>
        <tr class="resultado">
            <td><?php
echo CHtml::ajaxButton('Calcular Monto Neto', CHtml::normalizeUrl(array('presupuestosCheques/calcularMontoNeto', 'render' => false)), array(
    'type' => 'POST',
    'data' => array('tasaDescuento' => 'js:$("#PresupuestosCheques_tasaDescuento").val()',
        'clearing' => 'js:$("#PresupuestosCheques_clearing").val()',
        'pesificacion' => 'js:$("#PresupuestosCheques_pesificacion").val()',
        'montoOrigen' => 'js:$("#PresupuestosCheques_montoOrigen").val()',
        'fechaPago' => 'js:$("#PresupuestosCheques_fechaPago").val()',
        'fechaOperacion' => 'js:$("#PresupuestoOperacionesCheques_fecha").val()',
    ),
    'dataType' => 'text',
    'beforeSend' => 'js:function(){
					if($("#PresupuestosCheques_tasaDescuento").val()=="" || $("#PresupuestosCheques_clearing").val()=="" || $("#PresupuestosCheques_montoOrigen").val()=="" || $("#PresupuestosCheques_fechaPago").val()==""){
						alert("Algunos de los valores requeridos para calcular el monto neto no fueron ingresados. Por favor ingreselos");
						return false;
					}

					return true;
				}',
    'success' => 'js:function(data){
                    var datos = jQuery.parseJSON(data);
                    if(datos.estado!=-1){
    					$("#PresupuestosCheques_montoNeto").val(datos.montoNeto);
    					$("#PresupuestosCheuques_descValor").val(datos.descuentoTasa);
    					$("#PresupuestosCheuques_pesificValor").val(datos.descuentoPesific);
    					var tdtabla=$(".formulario").find(".resultado").find(".tablaresultado");
    					tdtabla.html("<table border=1 class=\"ui-widget ui-widget-content\"><thead class=\"ui-widget-header \"><tr><th>Monto Neto</th><th>Gastos por Tasa Descuento</th><th>Gastos por Pesificacion</th></tr></thead><tbody><tr><td>"+datos.montoNeto+"<td>"+datos.descuentoTasa+"</td><td>"+datos.descuentoPesific+"</td></tr></tbody></table>");
                    } else {
                        alert("El cheque a ingresar ya expiro");
                    }
				}',
));
?>
            </td>
            <td class='tablaresultado' colspan=3><?php echo $form->error($presupuestosCheque, 'montoNeto'); ?></td>
        </tr>
    </table>

    <?php
    //echo CHtml::hiddenField('fechaOperacion', '', array("id" => "fechaOperacion", "value" => ""));
    echo $form->hiddenField($presupuestosCheque, 'montoNeto', array('size' => 20, 'maxlength' => 17, 'readonly' => "readonly"));
    echo $form->hiddenField($presupuestosCheque, 'presupuesto', array('value'=>1));
    ?>
    <div class="row buttons">
        <?php
        echo CHtml::ajaxSubmitButton(Yii::t('presupuestosCheques', 'Agregar Cheque'), CHtml::normalizeUrl(array('presupuestosCheques/create', 'render' => false)), array(
            'data'=>'js:jQuery(this).parents("form").serialize()+"&fechaOperacion="+$("#PresupuestoOperacionesCheques_fecha").val()',
            'beforeSend' => 'js:function(){
                                        //$("#fechaOperacion").val($("#PresupuestoOperacionesCheques_fecha").val());
					if($("#PresupuestosCheques_tasaDescuento").val()=="" || $("#PresupuestosCheques_clearing").val()=="" || $("#PresupuestosCheques_montoOrigen").val()=="" || $("#PresupuestosCheques_fechaPago").val()==""){
						alert("Algunos de los valores requeridos para calcular el monto neto no fueron ingresados. Por favor ingreselos");
						return false;
					}
                    if(!esFechaValida($("#PresupuestosCheques_fechaPago"))){
                        alert("Fecha Invalida");
                        $("#PresupuestosCheques_fechaPago").focus();
                        return false;
                    }
					return true;
				}',
            'success' => 'js: function(data) {
                            var datos=jQuery.parseJSON(data);
                            if(datos.errores!==null)
                                $("#erroresCheque").html(datos.errores);
                            else{
                                $("#erroresCheque").html("");
                                LimpiarFormulario();
                                $("#PresupuestoOperacionesCheques_montoNetoTotal").val(datos.montoNetoTotal);
                                $("#montoNominalTotal").val(datos.montoNominalTotal);
                                $("#totalIntereses").val(datos.totalIntereses);
                                $("#totalPesificacion").val(datos.totalPesificacion);
                                $.fn.yiiGridView.update("tmp-cheques-grid");
                            }
						}'),array('tabindex'=>9)
        );
        ?>
        <?php echo CHtml::button("Limpiar formulario",array("onclick"=>"LimpiarFormulario()"));?>
    </div>

<?php $this->endWidget(); ?>

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'operaciones-cheques-form',
        'enableAjaxValidation' => false,
            ));
    ?>
    <div id="errores"><?php echo $form->errorSummary($model); ?></div>

        <table>
            <tr>
                <td><?php echo Chtml::label("Total Intereses","")?></td>
                <td><?php echo Chtml::label("Total Gastos de Pesificacion","")?></td>
                <td><?php echo $form->labelEx($model, 'montoNetoTotal'); ?></td>
                <td><?php echo Chtml::label("Total Monto Nominal","")?></td>
            </tr>
            <tr>
                <td><?php echo Chtml::textField("totalIntereses",Utilities::MoneyFormat($model->montoIntereses),array("readonly"=>"readonly",'size' => 15,'id'=>'totalIntereses'))?></td>
                <td><?php echo Chtml::textField("totalPesificacion",Utilities::MoneyFormat($model->montoPesificacion),array("readonly"=>"readonly",'size' => 15,'id'=>'totalPesificacion'))?></td>
                <td><?php echo $form->textField($model, 'montoNetoTotal', array('size' => 15, 'maxlength' => 15, 'readonly' => 'readonly','value'=>Utilities::MoneyFormat($model->montoNetoTotal))); ?></td>
                <td><?php echo Chtml::textField("montoNominalTotal",Utilities::MoneyFormat($model->montoNominalTotal),array("readonly"=>"readonly",'size' => 15,'id'=>'montoNominalTotal'))?></td>
            </tr>
        </table>

    <div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar Presupuesto' : 'Guardar Presupuesto'); ?>
            <?php
    echo CHtml::ajaxButton('Presupuesto en PDF',
            //Yii::app()->createUrl("cheques/filtrar", array("prueba"=>'$("#fechaIni").val()',)),
            CHtml::normalizeUrl(array('presupuestoOperacionesCheques/presupuesto', 'render' => false)), array(
        'type' => 'GET',
        'dataType' => 'text',
        'success' => 'js:function(data){
                        $("#result").html(data);
                        }',
    ))
    ?>
    </div>

<?php $this->endWidget(); ?>


</div><!-- form -->

