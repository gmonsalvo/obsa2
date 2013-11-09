<script>
	function cambioConcepto(concepto) {
		if(concepto.value == 3) {
			$("#chequeRechazadoDiv").show();
			$("#labelMonto").html("Monto Gasto Rechazo");
		} else {
			$("#chequeRechazadoDiv").hide();
			$("#labelMonto").html("Monto");
		}
	}
</script>


<div class="form">


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'detalle-pesificaciones-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<div id="erroresCreate">
		<?php echo $form->errorSummary($model); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'conceptoId'); ?>
		<?php echo $form->dropDownList($model,'conceptoId', CHtml::listData(ConceptosPesificaciones::model()->findAll("id!=1"), 'id', 'nombre'),array('prompt' => 'Seleccione un Concepto','onchange'=>'cambioConcepto(this)')); ?>
		<?php echo $form->error($model,'conceptoId'); ?>
	</div>

	<div id="chequeRechazadoDiv" style="display: none">
		<div class="row">
			<?php echo CHtml::label("Banco",'bancoId'); ?>
			<?php echo CHtml::dropDownList("Cheques[bancoId]","",CHtml::listData(Bancos::model()->findAll(), 'id', 'nombre'), array('id' => 'bancoId','prompt' => 'Seleccione un Banco')); ?>
		</div>
			<div class="row">
			<?php echo CHtml::label("Num Cheque",'numeroCheque'); ?>
			<?php echo CHtml::textField("Cheques[numeroCheque]"); ?>
		</div>
			<div class="row">
			<?php echo CHtml::label("Librador",'libradorId'); ?>
			<?php echo CHtml::dropDownList("Cheques[libradorId]","0",CHtml::listData(Libradores::model()->findAll(array("order"=>"denominacion")), 'id', 'denominacion'), array('id'=>'libradorId','prompt'=>'Seleccione un librador','style'=>'width: 250px'));	
			?>
		</div>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'monto',array("id"=>"labelMonto")); ?>
		<?php echo $form->textField($model,'monto'); ?>
		<?php echo $form->error($model,'monto'); ?>
	</div>
	<?php echo $form->hiddenField($model,"pesificacionId",array("value"=>isset($model->pesificacionId) ? $model->pesificacionId : $pesificacionId)); ?>
	<?php echo $form->hiddenField($model,"id",array("value"=>isset($_GET["id"]) ? $_GET["id"] : 0)); ?>
	<div class="row buttons">
		<?php
        echo CHtml::ajaxSubmitButton($model->isNewRecord ? 'Agregar detalle' : 'Editar detalle', CHtml::normalizeUrl(array('detallePesificaciones/create', 'render' => false)), array(
            'success' => 'js:function(data){
					var datos=jQuery.parseJSON(data);
					if(datos.status=="success"){
						$("#detallePesificacion").empty().html(datos.detallePesificaciones);
						$("#form_detalle").empty();
						$( "#dialog-form" ).dialog( "close" );
					} else {
						$("#erroresCreate").html(datos.errores);
					}
				}',
                ), array('id' => 'agregarDetalle'));
        ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->