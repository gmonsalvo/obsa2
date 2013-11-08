<?php

/**
 * This is the model class for table "chequesColocados_migra".
 *
 * The followings are the available columns in table 'chequesColocados_migra':
 * @property integer $ID
 * @property string $FECHA
 * @property integer $NRO
 * @property string $NROCHEQUE
 * @property integer $IDBANCO
 * @property string $CUENTA
 * @property double $IMPORTE
 * @property double $VALORNOMINAL
 * @property double $VALORACTUALTOTAL
 * @property double $VALORNOMINALTOTAL
 * @property double $PORCENTAJE
 * @property double $TASA
 * @property integer $DIAS
 */
class ChequesColocadosMigra extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ChequesColocadosMigra the static model class
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
		return 'chequesColocados_migra';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ID, NRO, IDBANCO, DIAS', 'numerical', 'integerOnly'=>true),
			array('IMPORTE, VALORNOMINAL, VALORACTUALTOTAL, VALORNOMINALTOTAL, PORCENTAJE, TASA', 'numerical'),
			array('NROCHEQUE, CUENTA', 'length', 'max'=>50),
			array('FECHA', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, FECHA, NRO, NROCHEQUE, IDBANCO, CUENTA, IMPORTE, VALORNOMINAL, VALORACTUALTOTAL, VALORNOMINALTOTAL, PORCENTAJE, TASA, DIAS', 'safe', 'on'=>'search'),
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
			'ID' => 'ID',
			'FECHA' => 'Fecha',
			'NRO' => 'Nro',
			'NROCHEQUE' => 'Nrocheque',
			'IDBANCO' => 'Idbanco',
			'CUENTA' => 'Cuenta',
			'IMPORTE' => 'Importe',
			'VALORNOMINAL' => 'Valornominal',
			'VALORACTUALTOTAL' => 'Valoractualtotal',
			'VALORNOMINALTOTAL' => 'Valornominaltotal',
			'PORCENTAJE' => 'Porcentaje',
			'TASA' => 'Tasa',
			'DIAS' => 'Dias',
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

		$criteria->compare('ID',$this->ID);
		$criteria->compare('FECHA',$this->FECHA,true);
		$criteria->compare('NRO',$this->NRO);
		$criteria->compare('NROCHEQUE',$this->NROCHEQUE,true);
		$criteria->compare('IDBANCO',$this->IDBANCO);
		$criteria->compare('CUENTA',$this->CUENTA,true);
		$criteria->compare('IMPORTE',$this->IMPORTE);
		$criteria->compare('VALORNOMINAL',$this->VALORNOMINAL);
		$criteria->compare('VALORACTUALTOTAL',$this->VALORACTUALTOTAL);
		$criteria->compare('VALORNOMINALTOTAL',$this->VALORNOMINALTOTAL);
		$criteria->compare('PORCENTAJE',$this->PORCENTAJE);
		$criteria->compare('TASA',$this->TASA);
		$criteria->compare('DIAS',$this->DIAS);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}