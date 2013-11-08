<?php

/**
 * This is the model class for table "cheques_migra".
 *
 * The followings are the available columns in table 'cheques_migra':
 * @property integer $Id
 * @property integer $NRO
 * @property integer $TIPO
 * @property string $FECHA
 * @property string $FECHAINICIO
 * @property string $FECHAVTO
 * @property integer $DIASADICIONALES
 * @property integer $DIAS
 * @property double $IMPORTEVTO
 * @property double $PESIFICACION
 * @property double $PGASTOS
 * @property double $GASTOS
 * @property double $IMPUESTOSBANCARIOS
 * @property double $IMPORTENETO
 * @property double $TNM
 * @property double $VALORACTUAL
 * @property double $INTERESES
 * @property integer $IDBANCO
 * @property string $NROCHEQUE
 * @property string $LIBRADOR
 * @property string $ENDOSANTE
 * @property string $CUIT
 * @property string $DESTINO
 * @property string $ESTADO
 * @property integer $COMPRADOA
 * @property string $SITUACION
 * @property integer $TRANSM
 * @property integer $ENTREGADO
 * @property integer $ENTREGADOA
 * @property integer $ASIENTORECHAZO
 * @property integer $ASIGNADO
 * @property integer $ASIGNADAOA
 * @property double $PORCENTAJE
 * @property string $FECHAASIG
 * @property double $NETO
 * @property integer $NROASIG
 * @property integer $OPASIGNRO
 * @property double $OPASIGIMP
 * @property integer $ASIGNADOTMP
 */
class ChequesMigra extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ChequesMigra the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cheques_migra';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id, NRO, TIPO, DIASADICIONALES, DIAS, IDBANCO, COMPRADOA, TRANSM, ENTREGADO, ENTREGADOA, ASIENTORECHAZO, ASIGNADO, ASIGNADAOA, NROASIG, OPASIGNRO, ASIGNADOTMP', 'numerical', 'integerOnly'=>true),
			array('IMPORTEVTO, PESIFICACION, PGASTOS, GASTOS, IMPUESTOSBANCARIOS, IMPORTENETO, TNM, VALORACTUAL, INTERESES, PORCENTAJE, NETO, OPASIGIMP', 'numerical'),
			array('NROCHEQUE, LIBRADOR, ENDOSANTE, CUIT, DESTINO, ESTADO, SITUACION', 'length', 'max'=>50),
			array('FECHA, FECHAINICIO, FECHAVTO, FECHAASIG', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, NRO, TIPO, FECHA, FECHAINICIO, FECHAVTO, DIASADICIONALES, DIAS, IMPORTEVTO, PESIFICACION, PGASTOS, GASTOS, IMPUESTOSBANCARIOS, IMPORTENETO, TNM, VALORACTUAL, INTERESES, IDBANCO, NROCHEQUE, LIBRADOR, ENDOSANTE, CUIT, DESTINO, ESTADO, COMPRADOA, SITUACION, TRANSM, ENTREGADO, ENTREGADOA, ASIENTORECHAZO, ASIGNADO, ASIGNADAOA, PORCENTAJE, FECHAASIG, NETO, NROASIG, OPASIGNRO, OPASIGIMP, ASIGNADOTMP', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'NRO' => 'Nro',
			'TIPO' => 'Tipo',
			'FECHA' => 'Fecha',
			'FECHAINICIO' => 'Fechainicio',
			'FECHAVTO' => 'Fechavto',
			'DIASADICIONALES' => 'Diasadicionales',
			'DIAS' => 'Dias',
			'IMPORTEVTO' => 'Importevto',
			'PESIFICACION' => 'Pesificacion',
			'PGASTOS' => 'Pgastos',
			'GASTOS' => 'Gastos',
			'IMPUESTOSBANCARIOS' => 'Impuestosbancarios',
			'IMPORTENETO' => 'Importeneto',
			'TNM' => 'Tnm',
			'VALORACTUAL' => 'Valoractual',
			'INTERESES' => 'Intereses',
			'IDBANCO' => 'Idbanco',
			'NROCHEQUE' => 'Nrocheque',
			'LIBRADOR' => 'Librador',
			'ENDOSANTE' => 'Endosante',
			'CUIT' => 'Cuit',
			'DESTINO' => 'Destino',
			'ESTADO' => 'Estado',
			'COMPRADOA' => 'Compradoa',
			'SITUACION' => 'Situacion',
			'TRANSM' => 'Transm',
			'ENTREGADO' => 'Entregado',
			'ENTREGADOA' => 'Entregadoa',
			'ASIENTORECHAZO' => 'Asientorechazo',
			'ASIGNADO' => 'Asignado',
			'ASIGNADAOA' => 'Asignadaoa',
			'PORCENTAJE' => 'Porcentaje',
			'FECHAASIG' => 'Fechaasig',
			'NETO' => 'Neto',
			'NROASIG' => 'Nroasig',
			'OPASIGNRO' => 'Opasignro',
			'OPASIGIMP' => 'Opasigimp',
			'ASIGNADOTMP' => 'Asignadotmp',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('Id',$this->Id);
		$criteria->compare('NRO',$this->NRO);
		$criteria->compare('TIPO',$this->TIPO);
		$criteria->compare('FECHA',$this->FECHA,true);
		$criteria->compare('FECHAINICIO',$this->FECHAINICIO,true);
		$criteria->compare('FECHAVTO',$this->FECHAVTO,true);
		$criteria->compare('DIASADICIONALES',$this->DIASADICIONALES);
		$criteria->compare('DIAS',$this->DIAS);
		$criteria->compare('IMPORTEVTO',$this->IMPORTEVTO);
		$criteria->compare('PESIFICACION',$this->PESIFICACION);
		$criteria->compare('PGASTOS',$this->PGASTOS);
		$criteria->compare('GASTOS',$this->GASTOS);
		$criteria->compare('IMPUESTOSBANCARIOS',$this->IMPUESTOSBANCARIOS);
		$criteria->compare('IMPORTENETO',$this->IMPORTENETO);
		$criteria->compare('TNM',$this->TNM);
		$criteria->compare('VALORACTUAL',$this->VALORACTUAL);
		$criteria->compare('INTERESES',$this->INTERESES);
		$criteria->compare('IDBANCO',$this->IDBANCO);
		$criteria->compare('NROCHEQUE',$this->NROCHEQUE,true);
		$criteria->compare('LIBRADOR',$this->LIBRADOR,true);
		$criteria->compare('ENDOSANTE',$this->ENDOSANTE,true);
		$criteria->compare('CUIT',$this->CUIT,true);
		$criteria->compare('DESTINO',$this->DESTINO,true);
		$criteria->compare('ESTADO',$this->ESTADO,true);
		$criteria->compare('COMPRADOA',$this->COMPRADOA);
		$criteria->compare('SITUACION',$this->SITUACION,true);
		$criteria->compare('TRANSM',$this->TRANSM);
		$criteria->compare('ENTREGADO',$this->ENTREGADO);
		$criteria->compare('ENTREGADOA',$this->ENTREGADOA);
		$criteria->compare('ASIENTORECHAZO',$this->ASIENTORECHAZO);
		$criteria->compare('ASIGNADO',$this->ASIGNADO);
		$criteria->compare('ASIGNADAOA',$this->ASIGNADAOA);
		$criteria->compare('PORCENTAJE',$this->PORCENTAJE);
		$criteria->compare('FECHAASIG',$this->FECHAASIG,true);
		$criteria->compare('NETO',$this->NETO);
		$criteria->compare('NROASIG',$this->NROASIG);
		$criteria->compare('OPASIGNRO',$this->OPASIGNRO);
		$criteria->compare('OPASIGIMP',$this->OPASIGIMP);
		$criteria->compare('ASIGNADOTMP',$this->ASIGNADOTMP);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}