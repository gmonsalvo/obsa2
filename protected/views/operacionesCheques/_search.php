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
		<?php echo $form->label($model,'operadorId'); ?>
		<?php echo $form->textField($model,'operadorId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'clienteId'); ?>
		<?php echo $form->textField($model,'clienteId'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'montoNetoTotal'); ?>
		<?php echo $form->textField($model,'montoNetoTotal',array('size'=>15,'maxlength'=>15)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'fecha'); ?>
		<?php echo $form->textField($model,'fecha'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'userStamp'); ?>
		<?php echo $form->textField($model,'userStamp',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'timeStamp'); ?>
		<?php echo $form->textField($model,'timeStamp'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sucursalId'); ?>
		<?php echo $form->textField($model,'sucursalId'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->