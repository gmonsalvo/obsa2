<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'documento'); ?>
		<?php echo $form->textField($model,'documento',array('size'=>11,'maxlength'=>11)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'denominacion'); ?>
		<?php echo $form->textField($model,'denominacion',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'direccion'); ?>
		<?php echo $form->textField($model,'direccion',array('size'=>60,'maxlength'=>100)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'localidadId'); ?>
		<?php echo $form->textField($model,'localidadId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'provinciaId'); ?>
		<?php echo $form->textField($model,'provinciaId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'actividadId'); ?>
		<?php echo $form->textField($model,'actividadId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'montoMaximo'); ?>
		<?php echo $form->textField($model,'montoMaximo',array('size'=>15,'maxlength'=>15)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Buscar'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->