<?php
/**
 * Bootstrap file
 */

try {
    error_reporting(E_ALL);
    defined('APPLICATION_ROOT') || define('APPLICATION_ROOT', realpath(dirname(__FILE__).'/..')); 
    defined('APPLICATION_PATH') || define('APPLICATION_PATH', APPLICATION_ROOT . '/application'); 

    set_include_path(implode(PATH_SEPARATOR, array( 
        realpath(APPLICATION_PATH.'/../library'), 
        get_include_path() 
    ))); 
    // uses include_path in php.ini
    require_once 'Zend/Loader/Autoloader.php';
    $autoloader = Zend_Loader_Autoloader::getInstance();
    $autoloader->suppressNotFoundWarnings(false);
    
    
    // not working :/
    $resourceLoader = new Zend_Loader_Autoloader_Resource(array( 
        'basePath'  => APPLICATION_PATH, 
        'namespace' => ''));
        
    $resourceLoader->addResourceType('form', 'forms/', 'Form_');
    $resourceLoader->addResourceType('model', 'models/', 'Model');
    
    // database configuration
    $params = array(
        'host' => 'localhost',
        'username' => 'tefkon1',
        'password' => 'zeda9koy',
        'dbname' => 'test'
    );
    
    // testing connection
    $db = Zend_Db::factory('PDO_MYSQL',$params);
    $db->getConnection();
    Zend_Db_Table::setDefaultAdapter($db);
    
    $options = array(
        'layout' => 'layout',
        'layoutPath' => './application/views/scripts',
        'contentKey' => 'content'
    );
    
    Zend_Layout::startMvc($options);
    // router
    $router = new Zend_Controller_Router_Rewrite();
    
    // front controller
    $controller = Zend_Controller_Front::getInstance();
    $controller->setControllerDirectory('./application/controllers')
               ->setRouter($router)
               ->throwExceptions(true)
               ->setBaseurl('/uus')
               ->setParam('noViewRenderer', true);
    
    // run the controller
    $controller->dispatch();
} catch (Exception $e){
    echo("Exception: ".$e->getMessage()." in ".$e->getFile()." on line ".$e->getLine()."\n");
}
?>