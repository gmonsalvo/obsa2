<?php
$this->breadcrumbs=array(
	'Cheques'=>array('adminCheques'),
	'Entrega/Devolucion',
);
?>
<script>
    function getChequesEntregaDevolucion(){

        if($("#OperacionesCheques_clienteId").val()!="" && $("#accion").val()!=""){
            $.ajax({
                type: 'POST',
                url: "<?php echo CController::createUrl('cheques/getChequesParaEntregaDevolucion') ?>",
                data:{'accion':$("#accion").val(),'clienteId': $("#OperacionesCheques_clienteId").val() },
                dataType: 'text',
                success:function(data){
                	$("#grid").html(data).show();
                }
            });
        } else {
        	$("#grid").html("").hide();
        	
        }
    }

    function habilitarSubmit() {
    	if($.fn.yiiGridView.getSelection("cheques-grid")!='')
    		$("#submitButton").attr("disabled",false);
        else
    		$("#submitButton").attr("disabled","disabled");

    }
 </script>
<h2>Entrega/Devolucion de Cheques</h2>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cheques-form',
	'enableAjaxValidation'=>false,
)); ?>
	<?php //echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo CHtml::label("Accion: ",'accion'); ?>
		<?php echo CHtml::dropDownList('accion', $select, 
              array('entrega' => 'Entrega', 'devolucion' => 'Devolucion'),
              array('empty' => '(Seleccione accion)','onchange'=>"getChequesEntregaDevolucion()")); ?>
	</div>

	<div class="row">

                <?php echo CHtml::label("Cliente", 'clienteId'); ?>
            <?php
                $this->widget('CustomEJuiAutoCompleteFkField', array(
                    'model' => new OperacionesCheques,
                    'attribute' => 'clienteId', //the FK field (from CJuiInputWidget)
                    // controller method to return the autoComplete data (from CJuiAutoComplete)
                    'sourceUrl' => Yii::app()->createUrl('/clientes/buscarRazonSocial', array("tipo[0]" => Clientes::TYPE_INVERSOR, "tipo[1]" => Clientes::TYPE_TOMADOR_E_INVERSOR)),
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
                    'htmlOptions' => array(
                        'tabindex' => 1),
                    'select' => 'getChequesEntregaDevolucion();',
                ));
                ?>
	</div>

<div id="grid" style="display:none">
<?php 
$dataProvider = Cheques::model()->search();
$this->renderPartial('chequesEntregaDevolucion', array('dataProvider' => $dataProvider, 'accion'=>"entrega"));
?>

</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton("Realizar Accion",array("disabled"=>"disabled","id"=>"submitButton")); ?>
	</div>
<?php $this->endWidget(); ?>	
</div><!-- form -->	
