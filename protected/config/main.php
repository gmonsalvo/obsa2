<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Gestion de Operatorias',
    //'sourceLanguage'=>'es_ar',
    // preloading 'log' component
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.vendors.*',
        'application.extensions.*',
        'application.extensions.formatCurrency.*',
        'application.modules.auditTrail.models.AuditTrail',
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool

        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'pascal098deal',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
        'auditTrail' => array(),
    ),
    'language' => 'es', // Este es el lenguaje en el que querés que muestre las cosas
    'sourceLanguage' => 'en', // este es el lenguaje por default de los archivos
    //'preload'=>array('log'),
    // application components
       'preload'=>array('log'),
    'components' => array(
       
        'session' => array(
            'timeout' => 300,
            'sessionName' => 'CapAdvSession'
        ),
        'user' => array(
            // enable cookie-based authentication
            'class' => 'WebUser',
            'allowAutoLogin' => true,
        ),

        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'authManager' => array(
            'class' => 'CDbAuthManager',
            'connectionID' => 'db',
        ),
        /*
          'db'=>array(
          'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
          ),
         */
// uncomment the following to use a MySQL database

        'db' => array(
            'connectionString' => 'mysql:host=127.0.0.1;port=3306;dbname=obsa2',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'eLaStIx.2oo7',
            'charset' => 'utf8',
             'enableProfiling' => true,
            'enableParamLogging' => true,
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CWebLogRoute',
                    'levels'=>'trace, info, error, warning',
                    'categories'=>'system.db.*',
                    'showInFireBug'=>false //true/falsefirebug only - turn off otherwise
                    //'filter' => 'CLogFilter'
                ),
                // uncomment the following to show log messages on web pages
            ),
        ),
        // 'clientScript' => array(
        //     // 'class' => 'ext.nlacsoft.NLSClientScript',
        //     // 'hashMode' => 'PATH', //PATH|CONTENT
        //     // 'bInlineJs' => false
        // ),
                'clientScript'=>array(
                      'scriptMap'=>array(
                          'register.js'=>'register.js',
                          'login.js'=>'login.js',
                      ),
                ),
    ),
   
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'webmaster@example.com',
    ),
);
