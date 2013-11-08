<style>
/*    #grid{
        position:relative;
        overflow: auto;
        height: 400px;
    }*/
</style>
<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('clientes-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<script>
    var killScroll = false; 
    var scrollGrid = function(){
        var el = $("#clientes-grid table.items tbody"); 
        if  (this.scrollHeight - $(this).scrollTop() < $(this).outerHeight() + 150){ 
             if (killScroll == false) {
                killScroll = true;
                $.ajax({
                    async: false,
                    url: "<?php echo CController::createUrl('clientes/scrollInversores') ?>?offset="+$('table.items >tbody >tr').length+"&razonSocial="+$("input[name='Clientes[razonSocial]']").val(),
                    type: "post",
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function(data){
                            if(data.length){
                                var classTR = $('table.items >tbody >tr:last').attr("class");
                                var cantidadElementos = $('table.items >tbody >tr').length;
                                for(var i=0; i < data.length; i++){
                                    var indice = cantidadElementos + i;
                                    if(classTR == "odd") classTR="even"; else classTR="odd";
                                    var checkbox = "<input class='select-on-check' value='"+data[i]['id']+"' id='clientes-grid_c0_"+indice+"' type='checkbox' name='clientes-grid_c0[]'>";
                                    el.append("<tr class="+classTR+"><td class='checkbox-column'>"+checkbox+"</td><td>"+data[i]['razonSocial']+"</td><td>"+data[i]['saldo']+"</td><td>"+data[i]['saldoColocaciones']+"</td><td>"+data[i]['tasaInversor']+"</td><td>"+data[i]['operador']+"</td></tr>");
                                }
                                killScroll = false; 
                            }
                                    
                    },
                });  
            } 
        }
    }

$(document).ready(function() {
    var elem = $("#clientes-grid");
    elem.scroll(scrollGrid);
});
$(document).ready(function() {
        $("table.items tbody").each(function(){
                if(this.scrollHeight<=300) this.style.height = "auto";
        })
});




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
        y = y.split('.');
        var z='';
        for(var i=0;i<y.length;i++)
            z=z+y[i];
        x = z.split(',');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        return x1 + x2;

    }

    function HabilitarRecolocacion(){
        if($.fn.yiiGridView.getSelection('cheques-grid')!=""){
            $("#btnRecolocacion").removeAttr('disabled');
            $("#idCheque").val($.fn.yiiGridView.getSelection('cheques-grid'));
        }
        else{
            $("#btnRecolocacion").attr('disabled','disabled');
            $("#idCheque").val('');
        }

    }

    function MostrarColocaciones(id){
        //si hay alguno seleccionado
        if($.fn.yiiGridView.getSelection(id)!=''){
            $.ajax({
                type: 'POST',
                url: "<?php echo CController::createUrl('cheques/chequesColocadosEnCliente') ?>",
                data:{'id':$.fn.yiiGridView.getSelection(id)},
                dataType: 'Text',
                success:function(data){
                    //var datos=data.split(";");
                    $('#colocacionesInversor').css("display", "block");
                    $('#colocacionesInversor').html(data);
                    $("#idCliente").val($.fn.yiiGridView.getSelection(id));
                    //$("#montoPorColocar").val(datos[1]);
                    //Limpiar(); //borro la tabla y selecciones que haya
                }
            });
        }
        else {
            $("#idCliente").val($.fn.yiiGridView.getSelection(id));
            $('#colocacionesInversor').hide();
        }
    }

</script>
<style>
#clientes-grid {
    height: 300px;
    overflow-x: hidden;
    overflow-y: auto;
}
</style>
<div class="form">

    <?php
    $this->beginWidget('zii.widgets.CPortlet', array(
        'id' => 'portletInversores',
        'title' => '',
    ));
    echo "<b>Inversores</b>";
    $this->endWidget("portletInversores");
    ?>

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'cheques-form',
        'enableAjaxValidation' => false,
        'method' => 'post',
        'action' => Yii::app()->createUrl("/colocaciones/editarColocacion")
            ));
    ?>

        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'clientes-grid',
            'dataProvider' => $clientes->searchInversoresConSaldoColocaciones(),
            'filter' => $clientes,
            'selectionChanged' => 'MostrarColocaciones',
            'afterAjaxUpdate'=>'function(){ 
                var elem = $(".grid-view table tbody"); 
                if(elem[0].scrollHeight<=300) 
                    elem.height("auto");
                $("#clientes-grid").scroll(scrollGrid);
            }',
            'columns' => array(
                array(
                    'header' => 'Inversor seleccionado',
                    'class' => 'CCheckBoxColumn',
                ),
                'razonSocial',
                array(
                    'name' => 'saldo',
                    'header' => 'Saldo Cta Cte',
                    'value' => 'Utilities::MoneyFormat($data->saldo)',
                ),
                array(
                    'name' => 'saldo',
                    'header' => 'Saldo en Colocaciones',
                    'value' => 'Utilities::MoneyFormat($data->saldoColocaciones)',
                ),
                'tasaInversor',
                array(
                    'name' => 'operadorId',
                    'header' => 'Operador',
                    'value' => '$data->operador->apynom',
                ),
            ),
            // 'htmlOptions' => array(
            //     'class' => 'grid-view mgrid_table',
            // ),
        ));
        ?>
    <div id="colocacionesInversor" style="display:none">
        <?php
        // $dataProvider = $cheques->searchChequesByEstado(Cheques::TYPE_EN_CARTERA_COLOCADO); //tipo de cheque en cartera colocado
        // $dataProvider->setPagination(false);
        $this->renderPartial('/cheques/chequesColocadosPorInversor', array(
            'dataProvider' => new CActiveDataProvider("Cheques"),'idCliente'=>0
        ));
        ?>

    </div>
    <?php echo CHtml::hiddenField('idCheque', '', array('id' => 'idCheque')) ?>
<?php echo CHtml::hiddenField('idCliente', '', array('id' => 'idCliente')) ?>
<?php echo CHtml::submitButton('Reemplazar', array('id' => 'btnRecolocacion', 'disabled' => 'disabled')); ?>
<?php $this->endWidget("cheques-form"); ?>
</div><!-- form -->