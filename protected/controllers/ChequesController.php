<?php

class ChequesController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('*'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update', 'admin', 'prueba', 'addnew', 'viewCheque', 'pesificaciones', 'filtrar',
                			'getMontos', 'adminCheques', 'updateAlta', 'updateBaja', 'updateEntrega', 'updateDevolucion',
                			'updateDestino', 'viewCheck', 'chequesColocadosEnCliente','getBotonera','updateCampos','viewHistorial',
                            'getCheque','entregaDevolucion','getChequesParaEntregaDevolucion','informeChequesEntregaDevolucionPDF',
                            'reporteComprados', 'cargarChequesCliente', 'chequesFinanciera'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionPrueba() {
        $model = new Cheques;
        $this->render('colocacion', array('model' => $model, 'clientes' => new Clientes));
    }

    public function actionAddnew() {
        $model = new Cheques;
        // Ajax Validation enabled
        $this->performAjaxValidation($model);
        // Flag to know if we will render the form or try to add
        // new jon.
        $flag = true;
        if (isset($_POST['Cheques'])) {
            $flag = false;
            $model->attributes = $_POST['Cheques'];

            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
                //Return an <option> and select it
                /* echo CHtml::tag('option',array (
                  'value'=>$model->jid,
                  'selected'=>true
                  ),CHtml::encode($model->jdescr),true); */
            }
        }
        if ($flag) {
            $this->renderPartial('createDialog', array('model' => $model,), false, true);
        }
    }

    public function actionCreate() {
        $model = new Cheques;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Cheques'])) {
            $model->attributes = $_POST['Cheques'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Cheques'])) {
            $model->attributes = $_POST['Cheques'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
        else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('Cheques');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Cheques('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Cheques']))
            $model->attributes = $_GET['Cheques'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionReporteComprados() {
        $model = new Cheques('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Cheques']))
            $model->attributes = $_GET['Cheques'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    public function actionPesificaciones() {
        $model = new Cheques('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Cheques']))
            $model->attributes = $_GET['Cheques'];

        $this->render('pesificaciones', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Cheques::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'cheques-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    protected function beforeValidate() {
        $this->userStamp = Yii::app()->user->model->username;
        $this->timeStamp = Date("Y-m-d h:m:s");
        return parent::beforeValidate();
    }

    public function actionViewCheque() {
        if ($_POST['id']) {
            $model = $this->loadModel($_POST['id']);

            // $html=$this->renderPartial('ver', array('model' => $model),true);
            //$valorActual=Colocaciones::model()->calculoValorActual($model->montoOrigen, Utilities::ViewDateFormat($model->fechaPago), $model->tasaDescuento, $model->clearing);
            $valorActual=Colocaciones::model()->calculoValorActual($model->montoOrigen, Utilities::ViewDateFormat($model->fechaPago), $_POST["tasa"], $_POST["clearing"]);
            $datos=array(
                "montoPorColocar"=>$model->montoOrigen,
                "clearing" => $model->clearing,
                "valorActual" => $valorActual,
                "diasColocados"=>  (Utilities::RestarFechas(Date("d-m-Y"), Utilities::ViewDateFormat($model->fechaPago))) + $_POST["clearing"]
            );
            echo CJSON::encode($datos);
            //echo $this->renderPartial('ver', array('model' => $model)) . ';' . $model->montoOrigen;
        }
    }

    public function actionFiltrar() {

        $model = new Cheques;
        $fechaIni = Utilities::MysqlDateFormat($_GET['fechaIni']);
        $fechaFin = Utilities::MysqlDateFormat($_GET['fechaFin']);
        $model->fechaIni = $fechaIni;
        $model->fechaFin = $fechaFin;
        $chequesId=array();
        if(isset($_GET["operacionChequeId"])){
            $operacionCheque=OperacionesCheques::model()->findByPk($_GET["operacionChequeId"]);
            
            foreach ($operacionCheque->cheques as $cheque)
                $chequesId[]=$cheque->id;
        }
        if(isset($_GET["estado"])){
            $dataProvider = $model->searchByFechaAndEstado ($fechaIni, $fechaFin, $_GET["estado"], $chequesId);
            $dataProvider->setPagination(false);
        }
        else
            {
                $dataProvider = $model->searchByFecha2($fechaIni, $fechaFin);
             }   

        $this->renderPartial('chequesEnCartera', array('cheques' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionGetMontos() {
        if (isset($_POST["chequesId"])) {
            $connection = Yii::app()->db;
            $command = Yii::app()->db->createCommand();

            $sql = "SELECT SUM(montoOrigen) as Total from cheques";
            $where = "";

            for ($i = 0; $i < count($_POST['chequesId']); $i++) {
                if ($i == (count($_POST['chequesId']) - 1)) // es el ultimo
                    $where.="id=" . $_POST['chequesId'][$i];
                else
                    $where.="id=" . $_POST['chequesId'][$i] . " OR ";

                //$sumaMontos=0;
                //$model=$this->loadModel($_POST["chequesId"][$i]);
            }
            $sql.=$where;
            $cheques = $command->select('SUM(montoOrigen) AS Total')->from('cheques')->where($where)->queryAll();
        }
        echo $cheques[0]["Total"];
    }

    /**
     * Manages all models.
     */
    public function actionAdminCheques() {
        $model = new Cheques('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Cheques']))
            $model->attributes = $_GET['Cheques'];

        $this->render('adminCheques', array(
            'model' => $model,
        ));
    }

    //alta de cheques rechazados
    /**
    *DEPRECATED
     */
    public function actionUpdateAlta($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Cheques'])) {
            $model->attributes = $_POST['Cheques'];
            if ($model->save())
                $this->redirect(array('viewCheck', 'id' => $model->id));
        }

        $this->render('updateAlta', array(
            'model' => $model,
        ));
    }

    //baja de cheques rechazados
    public function actionUpdateBaja($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Cheques'])) {
            $model->attributes = $_POST['Cheques'];
            $connection = Yii::app()->db;
            $transaction = $connection->beginTransaction();
            try {
                if ($model->save()) {
                    $flujoFondos = new FlujoFondos;
                    $flujoFondos->cuentaId = '6'; // corresponde caja de operaciones
                    $flujoFondos->conceptoId = 26;
                    $flujoFondos->descripcion = "Ingreso por monto nominal cheque ".$model->numeroCheque." por baja como cheque rechazado";
                    $flujoFondos->tipoFlujoFondos = FlujoFondos::TYPE_CREDITO;

                    $flujoFondos->monto = $model->montoOrigen;
                    $flujoFondos->saldoAcumulado = $flujoFondos->getSaldoAcumuladoActual() + $flujoFondos->monto; //tipo mov es credito
                    $flujoFondos->fecha = Date("d/m/Y");
                    $flujoFondos->origen = 'Cheques';
                    $flujoFondos->identificadorOrigen = $model->id;
                    $flujoFondos->sucursalId = Yii::app()->user->model->sucursalId;
                    $flujoFondos->userStamp = Yii::app()->user->model->username;
                    $flujoFondos->timeStamp = Date("Y-m-d h:m:s");
                    if(!$flujoFondos->save()) {
                        throw new Exception("Error Al efectuar el movimiento en caja de operaciones", 1);                        
                    }
                    $flujoFondos = new FlujoFondos;
                    $flujoFondos->cuentaId = '6'; // corresponde caja de operaciones
                    $flujoFondos->conceptoId = 26; // ???? cual usamos
                    $flujoFondos->descripcion = "Ingreso de gastos del cheque rechazado numero ".$model->numeroCheque." por baja como cheque rechazado";
                    $flujoFondos->tipoFlujoFondos = FlujoFondos::TYPE_CREDITO;

                    $flujoFondos->monto = $model->montoGastos;
                    $flujoFondos->saldoAcumulado = $flujoFondos->getSaldoAcumuladoActual() + $flujoFondos->monto; //tipo mov es credito
                    $flujoFondos->fecha = Date("d/m/Y");
                    $flujoFondos->origen = 'Cheques';
                    $flujoFondos->identificadorOrigen = $model->id;
                    $flujoFondos->sucursalId = Yii::app()->user->model->sucursalId;
                    $flujoFondos->userStamp = Yii::app()->user->model->username;
                    $flujoFondos->timeStamp = Date("Y-m-d h:m:s");
                    if(!$flujoFondos->save()) {
                        throw new Exception("Error Al efectuar el movimiento en caja de operaciones", 1);                        
                    }
                    $transaction->commit();
                    Yii::app()->user->setFlash('success', 'El cheque fue dado de baja como rechazado');
                    $this->redirect(array('viewCheck', 'id' => $model->id));
                }                
            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::app()->user->setFlash('error', $e->getMessage());
            }
        }

        $this->render('updateBaja', array(
            'model' => $model,
        ));
    }

    //entrega de cheque al cliente
    public function actionUpdateEntrega($id) {
        $model = $this->loadModel($id);

        if (isset($_POST['Cheques'])) {
            $model->attributes = $_POST['Cheques'];
            if ($model->save())
               Yii::app()->user->setFlash('success','El cheque esta ahora en poder del cliente');
                $this->redirect(array('adminCheques'));
        }

        $this->render('updateEntrega', array(
            'model' => $model,
        ));
    }

    //devolucion del cheque al cliente
    public function actionUpdateDevolucion($id) {
        $model = $this->loadModel($id);

        if (isset($_POST['Cheques'])) {
            $model->attributes = $_POST['Cheques'];
            if ($model->save()) 
               Yii::app()->user->setFlash('success','El cheque esta nuevamente en cartera');
                $this->redirect(array('adminCheques'));
        }

        $this->render('updateDevolucion', array(
            'model' => $model,
        ));
    }

    //cambiar destino del cheque
    public function actionUpdateDestino($id) {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Cheques'])) {
            $model->attributes = $_POST['Cheques'];
            if ($model->save())
				Yii::app()->user->setFlash('success','Se actualizo el destino del cheque');
                $this->redirect(array('adminCheques'));
        }

        $this->render('updateDestino', array(
            'model' => $model,
        ));
    }

    public function actionViewCheck($id) {
        $this->render('viewCheck', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionChequesColocadosEnCliente() {
        if (isset($_POST['id'])) {
            $model = new Cheques;
            $dataProvider=$model->searchChequesColocadosPorInversor($_POST['id'][0]);
            $this->renderPartial('chequesColocadosPorInversor', array('dataProvider' => $dataProvider, 'idCliente'=>$_POST['id'][0]));

        } else {
            if(isset($_GET['id'])) {
                $id = $_GET['id'];
                $model = new Cheques;
                $dataProvider=$model->searchChequesColocadosPorInversor($id);
                $this->renderPartial('chequesColocadosPorInversor', array('dataProvider' => $dataProvider, 'idCliente'=>$id));
            }
        }

    }

	//activa o desactiva botones de la botonera de la administracion de cheques
	public function actionGetBotonera() {
       $id=$_POST['id'];
	   //obtenemos el id del cheque que viene en el post
	   $model=$this->loadModel($id);
	   //analizamos el estado del cheque
	   $strJS="";

       ##lo deshabilito por defecto al boton levantar, solo lo habilito para cuando sea estado colocado y tipo levantar
       $strJS=$strJS."$('#botonLevantar').attr('disabled', true);";
	   if ($model->estado==Cheques::TYPE_ACREDITADO){
	   $strJS="$('#botonCambiarDestino').attr('disabled', true); /* vendido */ ";
	   $strJS=$strJS."$('#botonReemplazar').attr('disabled', true);";
	   $strJS=$strJS."$('#botonEntregaCliente').attr('disabled', true);";
	   $strJS=$strJS."$('#botonDevolucionCliente').attr('disabled', true);";
	   //$strJS=$strJS."$('#botonAltaRechazado').attr('disabled', false);";
	   $strJS=$strJS."$('#botonBajaRechazado').attr('disabled', false);";
	   $strJS=$strJS."$('#botonHistorial').attr('disabled', false);";
	   }
	   if ($model->estado==Cheques::TYPE_EN_CARTERA_COLOCADO){
	   $strJS="$('#botonCambiarDestino').attr('disabled', false); /*encartera colocado*/ ";
	   $strJS=$strJS."$('#botonReemplazar').attr('disabled', false);";
	   $strJS=$strJS."$('#botonEntregaCliente').attr('disabled', false);";
	   $strJS=$strJS."$('#botonDevolucionCliente').attr('disabled', true);";
	   //$strJS=$strJS."$('#botonAltaRechazado').attr('disabled', true);";
	   $strJS=$strJS."$('#botonBajaRechazado').attr('disabled', true);";
	   $strJS=$strJS."$('#botonHistorial').attr('disabled', false);";
       $strJS=$strJS."$('#botonLevantar').attr('disabled', false);";
	   }
	   if ($model->estado==Cheques::TYPE_EN_CARTERA_SIN_COLOCAR){
	   $strJS="$('#botonCambiarDestino').attr('disabled', false); /*encartera sin colocar*/ ";
	   $strJS=$strJS."$('#botonReemplazar').attr('disabled', true);";
	   $strJS=$strJS."$('#botonEntregaCliente').attr('disabled', true);";
	   $strJS=$strJS."$('#botonDevolucionCliente').attr('disabled', true);";
	   $strJS=$strJS."$('#botonAltaRechazado').attr('disabled', true);";
	   //$strJS=$strJS."$('#botonBajaRechazado').attr('disabled', true);";
	   $strJS=$strJS."$('#botonHistorial').attr('disabled', false);";
	   }
		if ($model->estado==Cheques::TYPE_EN_CLIENTE_INVERSOR){
	   $strJS="$('#botonCambiarDestino').attr('disabled', false); /*encliente inversor */ ";
	   $strJS=$strJS."$('#botonReemplazar').attr('disabled', false);";
	   $strJS=$strJS."$('#botonEntregaCliente').attr('disabled', true);";
	   $strJS=$strJS."$('#botonDevolucionCliente').attr('disabled', false);";
	   //$strJS=$strJS."$('#botonAltaRechazado').attr('disabled', true);";
	   $strJS=$strJS."$('#botonBajaRechazado').attr('disabled', true);";
	   $strJS=$strJS."$('#botonHistorial').attr('disabled', false);";
	   }
	   if ($model->estado==Cheques::TYPE_EN_PESIFICADOR){
	   $strJS="$('#botonCambiarDestino').attr('disabled', true); /*encliente inversor */ ";
	   $strJS=$strJS."$('#botonReemplazar').attr('disabled', true);";
	   $strJS=$strJS."$('#botonEntregaCliente').attr('disabled', true);";
	   $strJS=$strJS."$('#botonDevolucionCliente').attr('disabled', true);";
	   //$strJS=$strJS."$('#botonAltaRechazado').attr('disabled', false);";
	   $strJS=$strJS."$('#botonBajaRechazado').attr('disabled', false);";
	   $strJS=$strJS."$('#botonHistorial').attr('disabled', false);";
	   }
	   //finalmente escribimos el javascript
	   echo ($strJS);
    }

	public function actionUpdateCampos() {
       if (isset($_POST["boton"])) {
		    $id=$_POST['chequeId'];

		   //renderizacion la accion
		  if ($_POST["boton"] == "Cambiar Destino"){
			$this->redirect(array('updateDestino','id'=>$id));
		  }
  		  if ($_POST["boton"] == "Entregar al Cliente"){
			$this->redirect(array('updateEntrega','id'=>$id));
		  }
		  if ($_POST["boton"] == "Devolucion del Cliente"){
			$this->redirect(array('updateDevolucion','id'=>$id));
		  }
	   	  if ($_POST["boton"] == "Baja como Rechazado"){
			$this->redirect(array('updateBaja','id'=>$id));
		  }
          /** DEPRECATED */
		 //  if ($_POST["boton"] == "Alta como Rechazado"){
			// $this->redirect(array('updateAlta','id'=>$id));
		 //  }
		  if ($_POST["boton"] == "Historial"){

			$this->redirect(array('viewHistorial','id'=>$id));
		  }
         if ($_POST["boton"] == "Reemplazar"){
			$this->redirect(array('colocaciones/editarColocacion','idCheque'=>$id));
		  }
        if ($_POST["boton"] == "Acreditar"){
            $this->redirect(array('ordenIngreso/levantarCheque','id'=>$id));
          }
	   } // fin del if isset boton
	}

	  //Historial del cheque cuando se compro, cuando y aqui se coloco y cuando se pesifico y vendio.
    public function actionViewHistorial($id) {
	    //obtenmos los datos del cheque
         $cheque = $this->loadModel($id);
		 $criteria = new CDbCriteria;
		 $criteria->condition = 'chequeId=:chequeId';
         $criteria->order = 'id'; // correct order-by field
         $criteria->params = array(':chequeId' => $cheque->id);
		  //obtenemos los datos de las colocaciones

         $colocaciones = Colocaciones::model()->findAll($criteria);
         $criteria2=new CDbCriteria;
         $criteria2->condition="chequeId=:chequeId";
         $criteria2->join="LEFT JOIN detallePesificaciones ON detallePesificaciones.pesificacionId=t.id";
         $criteria2->params=array(":chequeId"=>$id);

         // if($cheque->estado==Cheques::TYPE_RECHAZADO){
         //     $chequeRechazado=ChequesRechazados::model()->findByAttributes(array("chequeId"=>$cheque->id));
         // }

         $pesificacion=  Pesificaciones::model()->find($criteria2);
		  $this->render('viewHistorial', array(
            'cheque' => $cheque, 'colocaciones'=>$colocaciones, 'pesificacion'=>$pesificacion) //,'colocacion'=>$colocacion,'detalleColocacion'=>$detalleColocacion,'detallePesificacion'=>$detallePesificacion
			);
		}

    public function actionGetCheque() {
        if ($_POST['id']) {
            $model = $this->loadModel($_POST['id']);
            $datos=array(
                "chequeId"=>$model->id,
                "librador"=>$model->librador->denominacion,
                "fechaPago"=>  Utilities::ViewDateFormat($model->fechaPago),
                "montoOrigen"=>$model->montoOrigen,
                "numeroCheque"=>$model->numeroCheque,
            );
            echo CJSON::encode($datos);
            //echo $this->renderPartial('ver', array('model' => $model)) . ';' . $model->montoOrigen;
        }
    }

    public function actionEntregaDevolucion() {
        if($_POST["chequesSeleccionados"]) {
            $estado = $_POST["accion"] == "entrega" ? Cheques::TYPE_EN_CLIENTE_INVERSOR : Cheques::TYPE_EN_CARTERA_COLOCADO;
            $clienteId = $_POST["OperacionesCheques_clienteId"];
            $criteria = new CDbCriteria;
            $criteria->addInCondition("id", $_POST["chequesSeleccionados"]);
            Cheques::model()->updateAll(array('estado'=>$estado),$criteria);
            Yii::app()->user->setFlash('success','Se realizo la '.$_POST["accion"].' de los cheques seleccionados al cliente '.$_POST["clienteId_save"]);
            
            $chequesSeleccionados = urlencode(serialize($_POST["chequesSeleccionados"]));
            $ejecutar = '<script type="text/javascript" language="javascript">
            window.open("'.Yii::app()->createUrl("/cheques/informeChequesEntregaDevolucionPDF", array("accion"=>$_POST["accion"],"chequesSeleccionados"=>$chequesSeleccionados,"clienteId"=>$clienteId)).'");
            </script>';

            Yii::app()->session['ejecutar'] = $ejecutar;
        }
        if(isset(Yii::app()->session["ejecutar"])){
            echo Yii::app()->session["ejecutar"];
            unset(Yii::app()->session['ejecutar']);
        }
        $this->render('entregaDevolucion');   
    }

    public function actionGetChequesParaEntregaDevolucion() {
        if($_POST) {
            $estado = $_POST["accion"] == "entrega" ? Cheques::TYPE_EN_CARTERA_COLOCADO : Cheques::TYPE_EN_CLIENTE_INVERSOR;
            $clienteId = $_POST["clienteId"];
            $cliente = Clientes::model()->findByPk($clienteId);
            $dataProvider = Cheques::model()->getChequesEntregaDevolucion($estado,$clienteId);
            
            $this->renderPartial('chequesEntregaDevolucion', array('dataProvider' => $dataProvider, 'accion'=>$_POST["accion"], "razonSocial"=>$cliente->razonSocial));
  
        }
    }

    public function actionInformeChequesEntregaDevolucionPDF() {

        if (isset($_GET["accion"]) && isset($_GET["clienteId"])) {
            $estado = $_GET["accion"] == "entrega" ? Cheques::TYPE_EN_CARTERA_COLOCADO : Cheques::TYPE_EN_CLIENTE_INVERSOR;
            $clienteId = $_GET["clienteId"];
            $chequesSeleccionados = unserialize(urldecode($_GET["chequesSeleccionados"]));
            $cliente = Clientes::model()->findByPk($clienteId);
            $criteria = new CDbCriteria;
            $criteria->addInCondition("id", $chequesSeleccionados);
            $cheques = Cheques::model()->findAll($criteria);

            error_reporting(E_ALL & ~E_NOTICE);
            $pdf = Yii::createComponent('application.extensions.tcpdf.ETcPdf', 'P', 'cm', 'A4', true, 'UTF-8');
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor("CAPITAL ADVISORS");
            $pdf->SetTitle("Informe de entrega/devolucion de cheques");
            $pdf->SetKeywords("TCPDF, PDF, example, test, guide");
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->SetFont("times", "B", 16);
            $pdf->Write(0, Date("d/m/Y"), '', 0, 'R', true, 0, false, false, 0);
            $pdf->Cell(0, 3, ucwords($_GET["accion"])." de valores", 0, 1, 'C');

            $html="<p>Se realiz&oacute; la ".$_GET["accion"]." de los siguientes valores para el cliente ".$cliente->razonSocial.": </p><br />";
            $html.='<table border="1" cellpadding="2">';
            $html.='<tr>';
            $html.='
            <td align="center" style="width:15%">Nro. Cheque</td>
            <td align="center" style="width:15%">Fecha Vto.</td>
            <td align="center" style="width:20%">Valor Nominal</td>
            <td align="center" style="width:35%">Librador</td>
            <td align="center" style="width:15%">Tipo Cheque</td>            
            </tr>';

            foreach($cheques as $row){
                $html.="<tr>";
                $html.="<td>".$row->numeroCheque."</td>";
                $html.="<td>".Utilities::ViewDateFormat($row->fechaPago)."</td>";
                $html.='<td align="right">'.Utilities::MoneyFormat($row["montoOrigen"]).'</td>';
                $html.="<td>".ucwords(strtolower($row->librador->denominacion))."</td>";
                $html.='<td align="right">'.$row->getTypeDescription("tipoCheque").'</td>';
                $html.="</tr>";
            }
            $html.="</table><br />";
            //$pdf->writeHTML($estilos, true, false, true, false, '');
            $pdf->SetFont("times", "", 11);
            //echo $html;
            $pdf->writeHTML($html, true, false, true, false, '');
            $pdf->Ln();
            $pdf->writeHTML("---------------------------", true, false, false, false, "R");
            
            if($_GET["accion"]=="entrega")
                $pdf->writeHTML("Firma Cliente", true, false, false, false, "R");
            else
                $pdf->writeHTML("Firma Responsable", true, false, false, false, "R");
            $pdf->Output("export.pdf", "I");
        }
    }

	public function actionCargarChequesCliente() {
		
		//var_dump($_POST["OperacionesCheques"]["clienteId"]);
		
		
		$modelo = new Cheques;
		
		/*
		$cliente = Clientes::model()->findByPk($_POST['Ctacte']['pkModeloRelacionado']);
		
		foreach($cliente->productos as $producto)
			echo CHtml::tag('option', array('value'=>$producto->id),CHtml::encode($producto->nombre),true);
		*/
	}
	
	public function actionChequesFinanciera() {
				
		$modeloOperacionesCheques = new OperacionesCheques;
		$modeloOperacionesCheques->init();
		
		$modelo = new Cheques('search');
		$modelo->unsetAttributes();
		
        if (isset($_GET['Cheques']))
            $model->attributes = $_GET['Cheques'];
		/*
		if (isset($_GET['ajax'])) {
			echo "PEPE";
			exit;
		}*/
		
		$this->render('chequesFinanciera', array('modeloOperacionesCheques' => $modeloOperacionesCheques, 'modelo' => $modelo));
	}
}
