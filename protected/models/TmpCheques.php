<?php

/**
 * This is the model class for table "tmpCheques".
 *
 * The followings are the available columns in table 'tmpCheques':
 * @property string $id
 * @property string $tasaDescuento
 * @property integer $clearing
 * @property string $pesificacion
 * @property string $numeroCheque
 * @property integer $libradorId
 * @property integer $bancoId
 * @property string $montoOrigen
 * @property string $fechaPago
 * @property integer $tipoCheque
 * @property string $endosante
 * @property string $montoNeto
 * @property integer $estado
 * @property string $userStamp
 * @property string $timeStamp
 * @property integer $sucursalId
 * @property integer $tieneNota
 * @property integer $presupuesto
 */
class TmpCheques extends CustomCActiveRecord {
    /**
     * Returns the static model of the specified AR class.
     * @return TmpCheques the static model class
     */
    //Tipo del cheque
    const TYPE_VENTANILLA=0;
    const TYPE_CRUZADO=1;
    const TYPE_NO_A_LA_ORDEN=2;
    const TYPE_A_LEVANTAR=3;

    //Estado del cheque
    const TYPE_EN_CARTERA_SIN_COLOCAR=0;
    const TYPE_RECHAZADO=1;
    const TYPE_EN_CLIENTE_INVERSOR=2;
    const TYPE_ACREDITADO=3;
    const TYPE_EN_CARTERA_COLOCADO=4;
    const TYPE_EN_PESIFICADOR=5;
    const TYPE_CORRIENTE=6;
    const TYPE_INDEFINIDO=-1;

    const NUMERO_DECIMALES=2;

    private $descuentoTasa;
    private $descuentoPesific;
    private $diasAlVenc=0;

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tmpCheques';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('tasaDescuento, libradorId, bancoId, montoOrigen, fechaPago, estado, userStamp, timeStamp, sucursalId, numeroCheque, tipoCheque', 'required'),
            array('clearing, bancoId, tipoCheque, estado, sucursalId, numeroCheque, tieneNota, presupuesto', 'numerical', 'integerOnly' => true),
            array('montoOrigen,pesificacion', 'numerical'),
            array('libradorId', 'numerical', 'min'=>1),
            array('tasaDescuento, pesificacion', 'length', 'max' => 5),
            array('numeroCheque', 'length', 'max' => 45),
            array('montoOrigen, montoNeto', 'length', 'max' => 15),
            array('montoOrigen', 'validateMontoOrigen'),
            array('endosante', 'length', 'max' => 100),
            //array('timeStamp', 'default', 'value'=>date("d-m-Y h:m:s"),'setOnEmpty'=>false,'on'=>'insert'),
            array('fechaPago, timeStamp', 'safe'),
            array('numeroCheque', 'validateCheque'),
            array('libradorId', 'validateMontoMaximoLibrador'),
            // array('fechaPago', 'date','message' => '{attribute}: El formato de la fecha es incorrecto!','format'=>array('yyyy/mm/dd','yyyy/m/dd'), 'allowEmpty'=>false),
            //array('fechaPago', 'type', 'type' => 'date', 'message' => '{attribute}: El formato de la fecha es incorrecto!', 'dateFormat' => 'dd/MM/yyyy'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, tasaDescuento, clearing, pesificacion, numeroCheque, libradorId, bancoId, montoOrigen, fechaPago, tipoCheque, endosante, montoNeto, estado, userStamp, timeStamp, sucursalId, tieneNota', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'banco' => array(self::BELONGS_TO, 'Bancos', 'bancoId'),
            'librador' => array(self::BELONGS_TO, 'Libradores', 'libradorId'),
            'sucursal' => array(self::BELONGS_TO, 'Sucursales', 'sucursalId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'tasaDescuento' => 'Tasa Descuento',
            'clearing' => 'Clearing',
            'pesificacion' => 'Pesificacion',
            'numeroCheque' => 'Numero Cheque',
            'libradorId' => 'Librador',
            'bancoId' => 'Banco',
            'montoOrigen' => 'Valor Nominal',
            'fechaPago' => 'Fecha Pago',
            'tipoCheque' => 'Tipo Cheque',
            'endosante' => '2do Endoso',
            'montoNeto' => 'Monto Neto',
            'estado' => 'Estado',
            'userStamp' => 'User Stamp',
            'timeStamp' => 'Time Stamp',
            'sucursalId' => 'Sucursal',
            'tieneNota' => 'Tiene Nota',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('tasaDescuento', $this->tasaDescuento, true);
        $criteria->compare('clearing', $this->clearing);
        $criteria->compare('pesificacion', $this->pesificacion, true);
        $criteria->compare('numeroCheque', $this->numeroCheque, true);
        $criteria->compare('libradorId', $this->libradorId);
        $criteria->compare('bancoId', $this->bancoId);
        $criteria->compare('montoOrigen', $this->montoOrigen, true);
        $criteria->compare('fechaPago', $this->fechaPago, true);
        $criteria->compare('tipoCheque', $this->tipoCheque);
        $criteria->compare('endosante', $this->endosante, true);
        $criteria->compare('montoNeto', $this->montoNeto, true);
        $criteria->compare('estado', $this->estado);
        $criteria->compare('userStamp', $this->userStamp, true);
        $criteria->compare('timeStamp', $this->timeStamp, true);
        $criteria->compare('sucursalId', $this->sucursalId);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function searchByUserName() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->condition = "t.userStamp='" . Yii::app()->user->model->username . "' AND DATE(t.timeStamp)='" . Date('Y-m-d') . "'";
        $criteria->order = ' t.timeStamp DESC';
        //$criteria->compare('userStamp',Yii::app()->user->model->username);
        //$criteria->compare('DATE.(timeStamp)',Date('Y-m-d'));

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function searchByUserName2() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->condition = "t.userStamp='" . Yii::app()->user->model->username . "' AND DATE(t.timeStamp)='" . Date('Y-m-d') . "' AND presupuesto=0";
        $criteria->order = ' t.timeStamp DESC';
        //$criteria->compare('userStamp',Yii::app()->user->model->username);
        //$criteria->compare('DATE.(timeStamp)',Date('Y-m-d'));

        return $this->findAll($criteria);
    }

    protected function beforeValidate() {
        $this->sucursalId = Yii::app()->user->model->sucursalId;
        $this->userStamp = Yii::app()->user->model->username;
        $this->timeStamp = new CDbExpression('NOW()');
        //if ($this->isNewRecord)
        //$this->timeStamp = date("d-m-Y h:m:s");
        return parent::beforeValidate();
    }

    public function behaviors() {
        return array('datetimeI18NBehavior' => array('class' => 'ext.DateTimeI18NBehavior'),
                     'LoggableBehavior' => 'application.modules.auditTrail.behaviors.LoggableBehavior'); // 'ext' is in Yii 1.0.8 version. For early versions, use 'application.extensions' instead.
    }

    public function getTypeOptions($type) {
        switch ($type) {
            case 'tipoCheque':
                return array(
                    self::TYPE_VENTANILLA => 'Ventanilla',
                    self::TYPE_CRUZADO => 'Cruzado',
                    self::TYPE_NO_A_LA_ORDEN => 'No a la orden',
                    self::TYPE_A_LEVANTAR => 'A levantar',
                );
            case 'estado':
                return array(
                    self::TYPE_EN_CARTERA_SIN_COLOCAR => 'En cartera sin colocar',
                    self::TYPE_RECHAZADO => 'Rechazado',
                    self::TYPE_EN_CLIENTE_INVERSOR => 'En cliente inversor',
                    self::TYPE_ACREDITADO => 'Acreditado',
                    self::TYPE_EN_CARTERA_COLOCADO => 'En cartera colocado',
                    self::TYPE_EN_PESIFICADOR => 'En pesificador',
                    self::TYPE_CORRIENTE => 'Cheque corriente',
                );
            default:
                return array();
        }
    }

    public function getTypeDescription($type) {
        $options = array();
        $options = $this->getTypeOptions($type);
        return $options[$this->$type];
    }

    public function calcularMontoNeto($montoOrigen, $fechaPago, $tasaDescuento, $clearing, $pesificacion, $fechaOperacion) {
        $dFecIni = Date("d-m-Y");
        $futureDate=strtotime(date("Y-m-d", mktime()) . " + 365 day");
        $diasParaChequeCorriente=30;
        $margenChequeCorriente=$diasParaChequeCorriente*24*60*60;
        $fecPago = strtotime(Utilities::MysqlDateFormat($fechaPago));
        $fecOp = strtotime(Utilities::MysqlDateFormat($fechaOperacion));
        if(($fecPago > ($fecOp - $margenChequeCorriente)) && ($fecPago <= $fecOp)) { //si la fecha de pago es la del dia de hoy es un cheque corriente corresponde solo gastos de pesificacion
            $this->descuentoPesific = ($pesificacion / 100) * $montoOrigen;
            $resultado = $montoOrigen - $this->descuentoPesific;
            $this->descuentoTasa = 0;
            $estado=self::TYPE_CORRIENTE;
        } else {
            /* expirado */
            if($fecPago <= ($fecOp - $margenChequeCorriente))
                return array("estado" => self::TYPE_INDEFINIDO,"error"=>"El cheque ingresado ya expiro");
            else {
                if($fecPago > $futureDate)
                    return array("estado" => self::TYPE_INDEFINIDO,"error"=>"La fecha de pago no puede ser mas de un año en el futuro"); 
                else {
                    $dFecFin = $fechaPago;
                    $dFecIni = str_replace("-", "", $dFecIni);
                    $dFecIni = str_replace("/", "", $dFecIni);
                    $dFecFin = str_replace("-", "", $dFecFin);
                    $dFecFin = str_replace("/", "", $dFecFin);

                    preg_match("/([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})/", $dFecIni, $aFecIni);
                    preg_match("/([0-9]{1,2})([0-9]{1,2})([0-9]{2,4})/", $dFecFin, $aFecFin);

                    $date1 = mktime(0, 0, 0, $aFecIni[2], $aFecIni[1], $aFecIni[3]);
                    $date2 = mktime(0, 0, 0, $aFecFin[2], $aFecFin[1], $aFecFin[3]);

                    $dias = round(($date2 - $date1) / (60 * 60 * 24));

                    $C = $montoOrigen;
                    $n = $dias + $clearing;
                    $i = $tasaDescuento;
                    //$divisor=pow(1+($i/100),$n);
                    $this->descuentoPesific = $this->truncateFloat(($pesificacion / 100) * $C, self::NUMERO_DECIMALES);
                    $this->descuentoTasa = $this->truncateFloat($C * ((($tasaDescuento / 30) * $n) / 100), self::NUMERO_DECIMALES);
                    $resultado = $this->truncateFloat($C - $this->descuentoTasa - $this->descuentoPesific, self::NUMERO_DECIMALES);
                    $estado = self::TYPE_EN_CARTERA_SIN_COLOCAR;
                }
            }
        }
        $datos = array("montoNeto" => Utilities::MoneyFormat($resultado) ,"descuentoTasa" => Utilities::MoneyFormat($this->descuentoTasa) , "descuentoPesific" => Utilities::MoneyFormat($this->descuentoPesific), "estado" =>$estado);
        return $datos;
        //return $fechaOperacion.' '.$resultado.' '.$n.' '.($C/$divisor).' '.(($pesificacion/100)*$C);
    }

    public function getDescuentoTasa() {
        $this->descuentoTasa = $this->montoOrigen - $this->montoNeto - ($this->pesificacion / 100) * $this->montoOrigen;
        return $this->descuentoTasa;
    }

    public function getDescuentoPesific() {
        $this->descuentoPesific=Utilities::truncateFloat(($this->pesificacion / 100) * $this->montoOrigen, self::NUMERO_DECIMALES);
        return $this->descuentoPesific;
    }

    public function afterFind() {
        $this->descuentoTasa = $this->montoOrigen - $this->montoNeto - ($this->pesificacion / 100) * $this->montoOrigen;
        $this->descuentoPesific = ($this->pesificacion / 100) * $this->montoOrigen;
        return parent::afterFind();
    }

    public function validateCheque($attribute, $params) {

        //primero busco en tabla de cheques temporales
        $criteria = new CDbCriteria;
        $criteria->condition = "DATE(t.timeStamp)='" . Date('Y-m-d') . "' AND numeroCheque = :numeroCheque AND bancoId = :bancoId AND libradorId = :libradorId";
        $criteria->params=array(":numeroCheque"=>$this->numeroCheque,"bancoId"=>$this->bancoId, "libradorId"=>$this->libradorId);

        //si existe agrego el error
        if(TmpCheques::model()->exists($criteria)){
            $this->addError($this->numeroCheque, "El numero de cheque, banco y librador estan duplicados");
        } else {
            // segundo busco en tabla de cheques, excluyo los que fueron anulados
            $criteria = new CDbCriteria;
            $criteria->condition = "numeroCheque = :numeroCheque AND bancoId = :bancoId AND libradorId = :libradorId AND estado !=".Cheques::TYPE_ANULADO;
            $criteria->params=array(":numeroCheque"=>$this->numeroCheque,"bancoId"=>$this->bancoId, "libradorId"=>$this->libradorId);
            if(Cheques::model()->exists($criteria))
                $this->addError($this->numeroCheque, "El numero de cheque, banco y librador estan duplicados");
        }
    }

    public function validateMontoMaximoLibrador($attribute, $params) {
        if ($this->libradorId != '') {

            $sumaSQL1 = "SELECT SUM(montoOrigen) FROM cheques WHERE libradorId=" . $this->libradorId . " AND (estado=" . Cheques::TYPE_EN_CARTERA_SIN_COLOCAR . " OR estado=" . Cheques::TYPE_EN_CARTERA_COLOCADO . ")";
            $suma1 = Yii::app()->db->createCommand($sumaSQL1)->queryScalar();

            $sumaSQL2 = "SELECT SUM(montoOrigen) FROM tmpCheques WHERE libradorId=" . $this->libradorId . " AND estado=" . Cheques::TYPE_EN_CARTERA_SIN_COLOCAR . " AND DATE(timeStamp)='" . Date('Y-m-d') . "' AND presupuesto=0";
            $suma2 = Yii::app()->db->createCommand($sumaSQL2)->queryScalar();

            if ($this->librador->montoMaximo < ($suma1 + $suma2))
                $this->addError($this->libradorId, "Se supero el monto maximo que el librador puede tener en cheques en cartera");
        }
    }

    public function validateMontoOrigen($attribute, $params) {
        if ($this->montoOrigen <= 0)
            $this->addError($this->montoOrigen, "El valor nomimal debe ser mayor a cero");
    }

    public function truncateFloat($number, $digitos) {
        $raiz = 10;
        $multiplicador = pow($raiz, $digitos);
        $resultado = ((int) ($number * $multiplicador)) / $multiplicador;
        return $resultado;
        //return number_format($resultado, $digitos);
    }

    public function findChequesDelDia() {
        $criteria = new CDbCriteria();
        $criteria->condition = 'DATE(timeStamp)=:fechahoy AND userStamp=:username';
        $criteria->params = array(
            ':fechahoy' => Date('Y-m-d'),
            ':username' => Yii::app()->user->model->username);
        return TmpCheques::model()->findAll($criteria);
    }

    public function getDiasAlVenc(){
        $fechahoy = date ("d-m-Y");
        $this->diasAlVenc = Utilities::RestarFechas($fechahoy,$this->fechaPago) + $this->clearing;
        return $this->diasAlVenc;
    }

    public function esChequeCorriente($fechaPago, $fechaOperacion){
        $diasParaChequeCorriente=31;
        $margenChequeCorriente=$diasParaChequeCorriente*24*60*60;
        $fecPago = strtotime(Utilities::MysqlDateFormat($fechaPago));
        $fecOp = strtotime(Utilities::MysqlDateFormat($fechaOperacion));
        if(($fecPago > ($fecOp - $margenChequeCorriente)) && ($fecPago <= $fecOp))
            return true;       
        else
            return false;
    }

}