<?php

class CtacteClientesController extends Controller {

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
                'actions' => array('create', 'update', 'admin', 'delete', 'informeCtaCte', 'obtenerInforme','filtrar'),
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
    public function actionCreate() {
        $model = new CtacteClientes;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['CtacteClientes'])) {
            $model->attributes = $_POST['CtacteClientes'];
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

        if (isset($_POST['CtacteClientes'])) {
            $model->attributes = $_POST['CtacteClientes'];
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
            throw new CHttpException(400, 'Solicitud invalida. Por favor, no repita esta solicitud nuevamente.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $dataProvider = new CActiveDataProvider('CtacteClientes');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new CtacteClientes('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['CtacteClientes'])){
            $model->attributes = $_GET['CtacteClientes'];
            if (isset($_GET['fechaInicio'])){
                $model->fechaInicio=date('Y-m-d', CDateTimeParser::parse($_GET['fechaInicio'], 'dd/MM/yyyy'));
                $model->fechaFin=date('Y-m-d', CDateTimeParser::parse($_GET['fechaFin'], 'dd/MM/yyyy'));
            }else {
                $model->fechaInicio=date('Y-m-d', CDateTimeParser::parse($model->fechaInicio, 'dd/MM/yyyy'));
                $model->fechaFin=date('Y-m-d', CDateTimeParser::parse($model->fechaFin, 'dd/MM/yyyy'));
            }

          }  
        
        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = CtacteClientes::model()->findByPk((int) $id);
        if ($model === null)
            throw new CHttpException(404, 'La pagina solicitada no existe.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'ctacte-clientes-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionInformeCtaCte() {
        $model = new CtacteClientes;

        $this->render('informeCtaCte', array(
            'model' => $model,
        ));
    }

    public function actionObtenerInforme() {
        if (isset($_GET)) {
            $totalDepositos = CtacteClientes::model()->getTotalPorConcepto($_GET["clienteId"], 9);
            $totalRetiros = CtacteClientes::model()->getTotalPorConcepto($_GET["clienteId"], 16);

            $cliente = Clientes::model()->findByPk($_GET['clienteId']);
            $interesesDevengados = $cliente->montoColocaciones - $cliente->saldoColocaciones;

            echo "<br>";
            $this->beginWidget('zii.widgets.CPortlet', array(
                'title' => 'Resultados',
            ));
            echo "<b>Tasa Anual Promedio : </b> " . Colocaciones::model()->getTasaAnualPromedio($_GET["clienteId"]) . "%<br>";
            echo "<b>Total Depositos :</b> " . Utilities::MoneyFormat($totalDepositos) . "<br>";
            echo "<b>Total Retiros :</b> " . Utilities::MoneyFormat($totalRetiros) . "<br>";
            echo "<b>Intereses Devengados :</b> " . Utilities::MoneyFormat($interesesDevengados) . "<br>";
            $this->endWidget();
        }
    }

    public function actionFiltrar() {
        if(isset($_GET)){
            $model=new CtacteClientes();
            $model->clienteId=$_GET["clienteId"];
            $dataProvider=$model->searchByFechaAndCliente(Utilities::MysqlDateFormat($_GET["fechaIni"]),Utilities::MysqlDateFormat($_GET["fechaFin"]),$_GET["clienteId"]);
            $this->renderPartial('/ctacteClientes/gridCtaCteCliente', array('model' =>$model,
            'dataProvider' => $dataProvider,
        ));
        }
    }

}
