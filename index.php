<?php
/**
 * Busline application
 * 
 * @author Taivo Teder
 * @version 1.0
 */

try {
    error_reporting(E_ALL);
    defined('APPLICATION_ROOT') || define('APPLICATION_ROOT', realpath(dirname(__FILE__).'/..')); 
    defined('APPLICATION_PATH') || define('APPLICATION_PATH', APPLICATION_ROOT . '/application'); 

    set_include_path(
    	get_include_path() . PATH_SEPARATOR .
    	'./library' . PATH_SEPARATOR .
    	'./application' . PATH_SEPARATOR .
    	'./application/models'
    );

    // uses include_path in php.ini
    require_once 'Zend/Loader/Autoloader.php';
    $autoloader = Zend_Loader_Autoloader::getInstance();
    $autoloader->suppressNotFoundWarnings(false);

    /*
    $resourceLoader = new Zend_Loader_Autoloader_Resource(array( 
        'basePath'  => APPLICATION_PATH, 
        'namespace' => ''));
        
    $resourceLoader->addResourceType('form', 'forms/', 'Form_');
    $resourceLoader->addResourceType('model', 'models/', 'Model');*/
    
    // database configuration
    $params = array(
        'host' => 'localhost',
        'username' => 'username',
        'password' => 'password',
        'dbname' => 'test'
    );
    
    // testing connection
    $db = Zend_Db::factory('PDO_MYSQL',$params);
    Zend_Db_Table::setDefaultAdapter($db);
    
    $options = array(
        'layout' => 'layout',
        'layoutPath' => './application/views/scripts',
        'contentKey' => 'content'
    );
    
    Zend_Layout::startMvc($options);
    Zend_Registry::set('db', $db);
    // router
    $router = new Zend_Controller_Router_Rewrite();
    
    /**
     * setBaseUrl() needs to be configured
     */ 
    $controller = Zend_Controller_Front::getInstance();
    $controller->setControllerDirectory( './application/controllers')
               ->setRouter($router)
               ->throwExceptions(true)
               ->setBaseurl('/uus')
               ->setParam('noViewRenderer', true);
    
    /**
     * Router
     */ 
    // $config = new Zend_Config_Ini('./application/config.ini', 'production');
    // $router = Zend_Controller_Front::getInstance()->getRouter();
    // $router->addConfig($config, 'routes');
    /**
     * Run the controller
     */ 

    $controller->dispatch();
} catch (Exception $e){
    echo("Exception: ".$e->getMessage()." in ".$e->getFile()." on line ".$e->getLine()."\n");
}
?>