<script>
	function cambioConcepto(concepto) {
		if(concepto.value == 3) {
			$("#chequeRechazadoDiv").show();
		} else {
			$("#chequeRechazadoDiv").hide();
		}
	}
</script>

<?php //Yii::app()->getClientScript()->registerCoreScript('yiiactiveform');?>
<div class="form">

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<div id="erroresUpdate">
	<?php echo CHtml::errorSummary($model); ?>
	</div>
	<div class="row">
		<?php echo CHtml::label("Concepto",'conceptoId'); ?>
		<?php echo CHtml::dropDownList('conceptoId',$model->conceptoId, CHtml::listData(ConceptosPesificaciones::model()->findAll("id!=1"), 'id', 'nombre'),array('prompt' => 'Seleccione un Concepto','onchange'=>'cambioConcepto(this)')); ?>
		<?php //echo CHtml::error($model,'conceptoId'); ?>
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
			<?php
                // $this->widget('EJuiAutoCompleteFkField', array(
                //     'model' => new Cheques,
                //     'attribute' => 'libradorId', //the FK field (from CJuiInputWidget)
                //     'sourceUrl' => Yii::app()->createUrl('/libradores/buscarLibradores'),
                //     'showFKField' => false,
                //     'FKFieldSize' => 15,
                //     'relName' => 'librador', // the relation name defined above
                //     'displayAttr' => 'denominacion', // attribute or pseudo-attribute to display
                //     'autoCompleteLength' => 40,
                //     'options' => array(
                //         'minLength' => 1,
                //     ),
                //     'htmlOptions' => array(
                //         'tabindex' => 4)
                // ));
                ?>
		</div>
	</div>

	<div class="row">
		<?php echo CHtml::label("Monto",'monto'); ?>
		<?php echo CHtml::textField('monto',$model->monto,array("id"=>"monto")); ?>
		<?php //echo CHtml::error($model,'monto'); ?>
	</div>
	<?php echo CHtml::hiddenField("pesificacionId",$model->pesificacionId); ?>
	<?php echo CHtml::hiddenField("id",$model->id); ?>
	<div class="row buttons">
		<?php
        echo CHtml::ajaxButton($model->isNewRecord ? 'Agregar detalle' : 'Editar detalle', CHtml::normalizeUrl(array("/detallePesificaciones/update", "id" => $model->id)), array(
            'type' => 'GET',
            'data' => array(
            	'conceptoId' => 'js:$("#conceptoId").val()',
            	'pesificacionId' => 'js:$("#pesificacionId").val()',
            	'monto' => 'js:$("#monto").val()',
            	'id' => 'js:$("#id").val()',
             ),
             'dataType' => 'text',
            //
            //
            'success' => 'js:function(data){
					var datos=jQuery.parseJSON(data);
					if(datos.status=="success"){
						$("#detallePesificacion").empty().html(datos.detallePesificaciones);
						$("#form_detalle").empty();
						$( "#dialog-form" ).dialog( "close" );
					} else {
						$("#erroresUpdate").html(datos.errores);
					}
				}',
                ), array('id' => 'editarDetalle'));
        ?>
	</div>


</div><!-- form -->