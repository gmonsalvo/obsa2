<?php

/**
 * This is the model class for table "bancos_migra".
 *
 * The followings are the available columns in table 'bancos_migra':
 * @property integer $Id
 * @property string $CODIGO
 * @property string $NOMBRE
 * @property string $DOMICILIO
 * @property string $LOCALIDAD
 * @property string $PROVINCIA
 * @property string $CP
 * @property string $TELEFONO
 */
class BancosMigra extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return BancosMigra the static model class
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
		return 'bancos_migra';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('CODIGO, DOMICILIO, LOCALIDAD, PROVINCIA, CP', 'length', 'max'=>50),
			array('NOMBRE', 'length', 'max'=>100),
			array('TELEFONO', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('Id, CODIGO, NOMBRE, DOMICILIO, LOCALIDAD, PROVINCIA, CP, TELEFONO', 'safe', 'on'=>'search'),
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
			'CODIGO' => 'Codigo',
			'NOMBRE' => 'Nombre',
			'DOMICILIO' => 'Domicilio',
			'LOCALIDAD' => 'Localidad',
			'PROVINCIA' => 'Provincia',
			'CP' => 'Cp',
			'TELEFONO' => 'Telefono',
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
		$criteria->compare('CODIGO',$this->CODIGO,true);
		$criteria->compare('NOMBRE',$this->NOMBRE,true);
		$criteria->compare('DOMICILIO',$this->DOMICILIO,true);
		$criteria->compare('LOCALIDAD',$this->LOCALIDAD,true);
		$criteria->compare('PROVINCIA',$this->PROVINCIA,true);
		$criteria->compare('CP',$this->CP,true);
		$criteria->compare('TELEFONO',$this->TELEFONO,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}