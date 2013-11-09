<?php

// change the following paths if necessary
<<<<<<< HEAD
$yii=dirname(__FILE__).'/../yii/framework/yii.php';
=======
$yii=dirname(__FILE__).'/yii/framework/yii.php';
//$yii='/var/wwww/yii/framework/yii.php';
>>>>>>> 6cf5ec7ff543d3d76fce34d102faccb5f4bb404a

$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
// class WebApplication extends CWebApplication {
//   public function beforeControllerAction($controller,$action) {
//     if (!Yii::app()->request->isAjaxRequest) {
//       Yii::app()->clientScript->clearCache();
//     }
//     return true;
//   }
// }
//Yii::createApplication('WebApplication',$config)->run();

