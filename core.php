<?php

spl_autoload_register(function ($class_name) {
    $file = dirname(__FILE__) . '/../controllers/' . strtolower(str_replace('Controller', '', $class_name)) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
    
    $file = dirname(__FILE__) . '/../models/' .strtolower($class_name) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

require 'controller.php';
require 'input.php';
require 'model.php';
require 'view.php';
require 'routes.php';
require 'configs.php';

/**
 * If we host our code at http://localhost/my-php/index.php
 * And we visit http://localhost/my-php/test1/test?variable=1
 * We want this method to return the base URL: http://localhost/my-php/
 * To leverage it in URL building across the application
 * This is useful because it is difficult to derive it when rewriting URLs 
 * for them to be more human friendly
 */
function generateBaseURL(): string {
    // QUERY_STRING has values such as: /test1/test2&variable=32
    // for http://localhost:8888/my-php/test1/test2?variable=32
    // so we split it by & and select the string value sitting before &, in our example: /test1/test
    $separator = isset($_SERVER['QUERY_STRING']) ? explode('&', $_SERVER['QUERY_STRING'])[0] : '';
    // we then split REQUEST_URI by the above value if not empty, or otherwise if empty we use REQUEST_URI as is
    // for the above example, REQUEST_URI would be /my-php/test1/test2?variable=32
    // hence splitting by /test1/test, and getting the first value, gives /my-php
    $baseUrlPath = !empty($separator) ? explode($separator, $_SERVER['REQUEST_URI'])[0] : $_SERVER['REQUEST_URI'];
    // by combining /my-php with other variables on $_SERVER we can build the URL
    // REQUEST_SCHEME is http and HTTP_HOST is localhost:8888 in our example
    // hence the result is  http://localhost:8888/my-php
    $baseUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $baseUrlPath;
    $baseUrl = substr($baseUrl, 0, $baseUrl[strlen($baseUrl) - 1] == '/' ? strlen($baseUrl) - 1 : strlen($baseUrl));
    return $baseUrl;
}

function mvc(): void {
    if (!defined('BASE_URL')) {
        define('BASE_URL', generateBaseURL());
    }

    if (!defined('SRC_DIR')) {
        define('SRC_DIR', dirname(__FILE__) . '/..');
    }

    $path = isset($_SERVER['QUERY_STRING']) ? explode('/', $_SERVER['QUERY_STRING']) : [];
    if (!isset($path[1])) {
      $module = 'home';
      $page = 'index';
      $parameters = [];
    } else {
        $module = $path[1];
        $page = isset($path[2]) ? explode('&', $path[2])[0] : null;
        $page = $page ? str_replace('-', '_', $page) : null;
        $parameters = $path;
        unset($parameters[0], $parameters[1]);
        if (isset($parameters[2])) unset($parameters[2]);
        $parameters = array_values(array_filter($parameters, function($p) { return $p != '' && $p[0] != '&'; }));
        
    }
    Configs::init();
    $controller = routes($module);
    if (get_class($controller) !== 'SetupController') {
      require 'database.php';
    }
    $controller->init();
    $page = !$page || empty($page) ? 'index' : $page;
    $controller->routes($page, $parameters);
}



