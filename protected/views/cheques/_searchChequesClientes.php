<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id' => 'frmSearchChequesCliente',
	'enableClientValidation' => true,
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'post',
)); ?>
	<div class="row">
		<?php echo CHtml::label("Cliente", 'clienteId'); ?>
		<?php
            $this->widget('CustomEJuiAutoCompleteFkField', array(
                'model' => $modeloOperacionesCheques,
                'attribute' => 'clienteId', //the FK field (from CJuiInputWidget)
                // controller method to return the autoComplete data (from CJuiAutoComplete)
                'sourceUrl' => Yii::app()->createUrl('/clientes/buscarRazonSocial', array("tipo[0]" => Clientes::TYPE_FINANCIERA)),
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
                //'select' => 'getDatosCliente();',
                'htmlOptions' => array(
                    'tabindex' => 1),
                //'onSelectScript'=>CHtml::ajax(array('type'=>'POST', 'url'=>array("cheques/cargarChequesCliente"), 'update'=>'#contenedor')),
            ));
        ?>
	</div>
	<div class="row">
		<?php echo $form->label($modelo,'fechaPago'); ?>
		<?php
		    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
			        // you must specify name or model/attribute
			        'name' => 'fechaPago',
			         'model' => $modelo,
			        'attribute' => 'fechaPago',
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
			            'id' => 'fechaPago',
			            //'value' => '01/02/2013',
			            //'onChange' => 'js:if(esFechaValida($("#fechaInicio" ))); else { alert("Fecha Invalida"); $( "#fechaInicio" ).focus(); }',
			            //'style' => 'height:20px;'
			        )
	            )
		    );		
        ?>
	</div>

	
	<div class="row buttons">
		<?php echo CHtml::submitButton('Filtrar'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->