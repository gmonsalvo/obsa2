<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'orden-ingreso-form',
	'focus'=>array($model,'clienteId_save'),
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Campos con <span class="required">*</span> son obligatorios.</p>
<?php 


?>
	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'fecha'); ?>
		<?php
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
                'value' => Date("d/m/Y"),
                'readonly' => "readonly",
                'style' => 'height:20px;',
                'onChange' => 'js:$("#OrdenesIngreso_fecha").focus()',
            )
                )
        );
        ?>
		<?php echo $form->error($model,'fecha'); ?>
	</div>

	<div class="row">
	<?php
	echo $form->labelEx($model, 'Financiera');
	$this->widget('EJuiAutoCompleteFkField', array(
		  'model'=>$model, 
		  'attribute'=>'clienteId', //the FK field (from CJuiInputWidget)
		  // controller method to return the autoComplete data (from CJuiAutoComplete)
		  'sourceUrl'=>Yii::app()->createUrl('/clientes/buscarRazonSocial',array("tipo[3]"=>Clientes::TYPE_FINANCIERA)),
		  // defaults to false.  set 'true' to display the FK field with 'readonly' attribute.
		  'showFKField'=>true,
		   // display size of the FK field.  only matters if not hidden.  defaults to 10
		  'FKFieldSize'=>15, 
		  'relName'=>'cliente', // the relation name defined above
		  'displayAttr'=>'razonSocial',  // attribute or pseudo-attribute to display
		  // length of the AutoComplete/display field, defaults to 50
		  'autoCompleteLength'=>40,
		  // any attributes of CJuiAutoComplete and jQuery JUI AutoComplete widget may 
		  // also be defined.  read the code and docs for all options
		  'options'=>array(
			  // number of characters that must be typed before 
			  // autoCompleter returns a value, defaults to 2
			  'minLength'=>1, 
		  ),
	 ));
	 echo $form->error($model, 'clienteId');
	?>
	</div>
	

	<div class="row">
		<?php echo $form->labelEx($model,'monto'); ?>
		 <td><?php $this->widget("FormatCurrency",
                array(
                    "model" => $model,
                    "attribute" => "monto",
                   
                    ));
            ?>
		<?php echo $form->error($model,'monto'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'descripcion'); ?>
		<?php echo $form->textField($model,'descripcion',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'descripcion'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'productoId'); ?>
		<?php echo $form->dropDownList($model,'productoId',
        CHtml::listData(Productos::model()->findAll(), 'id', 'nombre'),array('empty'=>'')); ?>
		<?php echo $form->error($model,'productoId'); ?>
	</div>



	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Guardar Cambios'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->