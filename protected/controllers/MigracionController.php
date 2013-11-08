<?php

class MigracionController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('migrarClientes','migrarBancos','test','migrarCheques','migrarColocaciones','migrarSaldos'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Bancos('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Bancos']))
			$model->attributes=$_GET['Bancos'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	

	public function actionMigrarClientes() {
        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();
        try {


            $clientesM = new ClientesMigra();
            $i=10001;
            foreach ($clientesM->findAll() as $cm) {
               $cliente = new Clientes();
               $cliente->razonSocial=substr($cm->NOMBRE,0,44);
               if (is_null($cm->TELEFONO)){
               	$cliente->fijo="Completar....";
               }else{
               	$cliente->fijo=$cm->TELEFONO;
               }
              
               if (is_null($cm->DOMICILIO)){
               	$cliente->direccion="Completar....";
               }else{
               	$cliente->direccion=$cm->DOMICILIO;
               }

               $cliente->localidadId=194;
               $cliente->provinciaId=1;
               $cliente->email=substr($cm->EMAIL,0,44);
               $cliente->cuenta=$cm->CUENTA;
               $cliente->tasaInversor=24;
               $cliente->tipoCliente=2;
               $cliente->operadorId=1;
               $cliente->tasaTomador=3.5;
               $cliente->montoMaximoTomador=0;
               $cliente->tasaPesificacionTomador=2.5;
               $cliente->montoPermitidoDescubierto=0;
               $cliente->celular="Completar";

               //vemos si el documento esta nulo inventamos uno
               if ( (is_null($cm->CUIT)) or (!(is_numeric($cm->CUIT))) ) {
               			$cliente->documento=$i;
               		}	
               		else{
               			$cliente->documento=$cm->CUIT;
               	}

               	$cliente->userStamp="MIGRADOR";
               	$cliente->timeStamp=date("Y-m-d hh:mm:ss");
               	$cliente->sucursalId=1;


               if (!($cliente->save())){

               		echo "Error al guardar los datos del cliente".print_r($cliente->getErrors());
               		echo "Datos:".print_r($cliente->attributes)."<br>";
               		
               }
               echo $cm->NOMBRE."<br>";
               $i++;
            }
            $transaction->commit();
            echo "Fin.";
        } catch (Exception $e) {
            echo $e;
        }
    }


 public function actionMigrarSaldos() {
        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();
        try {


            $saldosM = new SaldosMigra();
            $i=10001;
            foreach ($saldosM->findAll() as $cm) {
               
						$sql = "INSERT INTO ctacteClientes
	                                            (tipoMov, conceptoId, clienteId, productoId, descripcion, monto, fecha, origen, identificadorOrigen, userStamp, timeStamp, sucursalId, saldoAcumulado)
	                                            VALUES (:tipoMov, :conceptoId, :clienteId, :productoId, :descripcion, :monto, :fecha, :origen, :identificadorOrigen, :userStamp, :timeStamp, :sucursalId, :saldoAcumulado)";

	                    if ($cm->SALDO>0){
	                    		$tipoMov = CtacteClientes::TYPE_CREDITO; //credito
	                    	}else{

	                    		$tipoMov = CtacteClientes::TYPE_DEBITO; //credito
	                    	}
	                    $conceptoId = 7; //Ingreso de fondos
	                    $descripcion = "Saldo Anterior Migrado";
	                    $fecha = Date("Y-m-d");
	                    $operacionRelacionada = "Migracion de Saldos";
	                    $origen="Migrador";
	                    $identificadorOrigen="1";

	                    $ctacteCliente = new CtacteClientes();
	                    //obtenemos el id de cliente apartir del numero de cuenta
	                    $cliente=Clientes::model()->find("cuenta=".$cm->CUENTA);
	                    $ctacteCliente->clienteId=$cliente->id;
	                    $saldoAcumuladoActual = $ctacteCliente->getSaldoAcumuladoActual();
	                    $saldoAcumulado=$saldoAcumuladoActual+$cm->SALDO;
	                    $userStamp = Yii::app()->user->model->username;
	                    $timeStamp = date("Y-m-d h:m:s");
	                    $sucursalId = Yii::app()->user->model->sucursalId;
	                    
	                    $command = $connection->createCommand($sql);
						$command->bindValue(":tipoMov", $tipoMov, PDO::PARAM_STR);
	                    $command->bindValue(":conceptoId", $conceptoId, PDO::PARAM_STR);
	                    $command->bindValue(":clienteId", $cliente->id, PDO::PARAM_STR);
	                    $command->bindValue(":productoId", "1", PDO::PARAM_STR);
	                    $command->bindValue(":descripcion", $descripcion, PDO::PARAM_STR);
	                    $command->bindValue(":monto", abs($cm->SALDO), PDO::PARAM_STR);
	                    $command->bindValue(":fecha", $fecha, PDO::PARAM_STR);
	                    $command->bindValue(":origen", $origen, PDO::PARAM_STR);
	                    $command->bindValue(":identificadorOrigen", $identificadorOrigen, PDO::PARAM_STR);
	                    $command->bindValue(":saldoAcumulado", $saldoAcumulado, PDO::PARAM_STR);
	                    $command->bindValue(":userStamp", $userStamp, PDO::PARAM_STR);
	                    $command->bindValue(":timeStamp", $timeStamp, PDO::PARAM_STR);
	                    $command->bindValue(":sucursalId", $sucursalId, PDO::PARAM_STR);
	                    $command->execute();	
               echo $cm->NOMBRE."<br>";
               $i++;
            }
            $transaction->commit();
            echo "Fin.";
        } catch (Exception $e) {
            echo $e;
        }
    }   

public function actionMigrarBancos() {
        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();
        try {


            $bancosM = new BancosMigra();
            $i=1;
            foreach ($bancosM->findAll() as $bm) {
               $banco = new Bancos();
               $banco->nombre=substr($bm->NOMBRE,0,44);
               $banco->idbanco=$bm->Id;
               $banco->userStamp="MIGRADOR";
               $banco->timeStamp=date("Y-m-d hh:mm:ss");
               $banco->sucursalId=1;


               if (!($banco->save())){

               		echo "Error al guardar los datos del banco".print_r($banco->getErrors());
               		echo "Datos:".print_r($banco->attributes)."<br>";
               		
               }
               echo $bm->NOMBRE."<br>";
               $i++;
            }
            $transaction->commit();
            echo "Fin.";
        } catch (Exception $e) {
            echo $e;
        }
    }

public function actionMigrarCheques() {
		set_time_limit(0);
		ini_set('memory_limit', '20000M');
        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();
        try {

            $chequesM = new ChequesMigra();
            $i=1;
            foreach ($chequesM->findAll() as $cm) {
               if ($cm->TNM>0){

	               $cheque = new Cheques();
	               $cheque->operacionChequeId=463;
	               $cheque->tasaDescuento=number_format($cm->TNM*100,2);
	               $cheque->clearing=$cm->DIASADICIONALES;
	               $cheque->pesificacion=$cm->PESIFICACION*100;
	               $cheque->numeroCheque=$cm->NROCHEQUE;
	               //[proceso de busqueda del librador]
	               //$librador=;
	               $cheque->libradorId=Libradores::model()->find("denominacion like '".$cm->LIBRADOR."'")->id;//$this->buscarLibrador($cm->LIBRADOR,$i);
	               //[proceso de busqueda del id de banco]
	               $banco=Bancos::model()->find("idbanco=".$cm->IDBANCO);
	               $cheque->bancoId=$banco->id;
	               $cheque->montoOrigen=$cm->IMPORTEVTO;
	               $cheque->fechaPago=$cm->FECHAVTO;
	               if ($cm->DESTINO=="LEVANTAR"){
	               		$cheque->tipoCheque=3;
	               }else{
		               	$cheque->tipoCheque=1;
	               }
	               $cheque->endosante=$cm->ENDOSANTE;
	               $cheque->montoNeto=$cm->IMPORTENETO;
	               $cheque->estado=4;
	               $cheque->tieneNota=0;
	               $cheque->comisionado=1;
	               $cheque->userStamp="MIGRADOR";
	               $cheque->timeStamp=date("Y-m-d hh:mm:ss");
	               $cheque->sucursalId=1;


	               if (!($cheque->save())){

	               		echo "Error al guardar los datos del cheque".print_r($cheque->getErrors());
	               		echo "Datos:".print_r($cheque->attributes)."<br>";
	               		
	               }
	               
	            } // fin del chequeo de los cheques "raros"   
	               echo "$i=".$i."< ".$cm->Id."--".$cm->LIBRADOR."<br>";
	               $i++;
            }
            $transaction->commit();
            echo "Fin.";
        } catch (Exception $e) {
            echo $e;
        }
    }

	public function actionMigrarColocaciones() {
        set_time_limit(0);
		ini_set('memory_limit', '20000M');
        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();
        try {


            $cheques  = new Cheques();
            $i=1;
            foreach ($cheques->findAll() as $c) {
               //traemos los datos de las colocaciones para el cheque actual
               $colocacionesMigradas=ChequesColocadosMigra::model()->findAll("NROCHEQUE like '".$c->numeroCheque."' AND IDBANCO=".$c->banco->idbanco);
               $chequeMigra=ChequesMigra::model()->findAll("NROCHEQUE like '".$c->numeroCheque."' AND IDBANCO=".$c->banco->idbanco);
               if (count($colocacionesMigradas)>0){
               	   //insertamos el registro cabecera
               	   $colocacion = new Colocaciones();
               	   $colocacion->fecha=$colocacionesMigradas[0]->FECHA;
               	   $colocacion->chequeId=$c->id;
               	   $colocacion->montoTotal=$colocacionesMigradas[0]->VALORNOMINALTOTAL;
               	    if ($cm->DESTINO=="LEVANTAR"){
	               		$colocacion->diasColocados=$colocacionesMigradas[0]->DIAS+$chequeMigra->DIASADICIONALES;
	               }else{
		               	$colocacion->diasColocados=$colocacionesMigradas[0]->DIAS;
	               }
               	   
               	   $colocacion->estado=1;
            	   $colocacion->sucursalId=1;
                   $colocacion->userStamp="MIGRADOR";
                   $colocacion->timeStamp=date("Y-m-d hh:mm:ss");
	               if (!($colocacion->save())){

               		echo "Error al guardar los datos de la cabecera de la colocacion".print_r($colocacion->getErrors());
               		echo "Datos:".print_r($colocacion->attributes)."<br>";
               		
	               }else{
               			   
               	   //llenamos el detalle
	               foreach ($colocacionesMigradas as $col) {
	               	$dc=new DetalleColocaciones();
	               	$dc->colocacionId=$colocacion->id;
	               	//obtenemos el id del cliente
	               	$cliente=Clientes::model()->find("cuenta=".$col->CUENTA);
	               	$dc->clienteId=$cliente->id;
	               	$dc->monto=$col->VALORNOMINAL;
	               	$dc->tasa=$col->TASA;
	               	if (!($dc->save())){

	               		echo "Error al guardar los datos del detalle de la colocacion".print_r($dc->getErrors());
	               		echo "Datos:".print_r($dc->attributes)."<br>";
	               		
	               	}

	               } // fin for recorrido de colocaciones para el cheque 	
	               }  // if guardado cabecera colocacion
           	   }//fin del IF si hay colocaciones para el cheque
               
               echo $c->librador->denominacion."<br>";
               $i++;
            }
            $transaction->commit();
            echo "Fin.";
        } catch (Exception $e) {
            echo $e;
        }
    }


    public function actionTest()
	{
		similar_text("CITROMAX SACI","CITROMA S.A.C.I",$similitud);
		echo $similitud;
	}

/*
	public function actionAdmin()
	{
		$model=new Apoderados('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Apoderados']))
			$model->attributes=$_GET['Apoderados'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
*/
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Apoderados::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	public function buscarLibrador($nombre,$num)
	{
      
       foreach (Libradores::model()->findAll() as $l) {
       	similar_text($l->denominacion,$nombre,$similar);
       	if ($similar>=80){
       		return $l->id;
       		
       	}

       }	
      
       	$librador= new Libradores();
       	$librador->denominacion=$nombre;
       	$librador->documento=10000+$num;
       	$librador->direccion="Completar";
       	$librador->localidadId=193;
       	$librador->provinciaId=1;
       	$librador->actividadId=1;
       	$librador->montoMaximo=100000;
        $librador->userStamp="MIGRADOR";
        $librador->timeStamp=date("Y-m-d hh:mm:ss");
        $librador->sucursalId=1;
		if (!($librador->save())){
       		echo "Error al guardar los datos del librador".print_r($librador->getErrors());
       		echo "Datos:".print_r($librador->attributes)."<br>";
        }	
       return $librador->id;
       


	}






	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='apoderados-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
