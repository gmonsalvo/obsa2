<?php

/**
 * This is the model class for table "saldos_migra".
 *
 * The followings are the available columns in table 'saldos_migra':
 * @property string $CUENTA
 * @property string $NOMBRE
 * @property double $SALDO
 */
class SaldosMigra extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return SaldosMigra the static model class
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
		return 'saldos_migra';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('SALDO', 'numerical'),
			array('CUENTA, NOMBRE', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('CUENTA, NOMBRE, SALDO', 'safe', 'on'=>'search'),
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
			'CUENTA' => 'Cuenta',
			'NOMBRE' => 'Nombre',
			'SALDO' => 'Saldo',
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

		$criteria->compare('CUENTA',$this->CUENTA,true);
		$criteria->compare('NOMBRE',$this->NOMBRE,true);
		$criteria->compare('SALDO',$this->SALDO);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}