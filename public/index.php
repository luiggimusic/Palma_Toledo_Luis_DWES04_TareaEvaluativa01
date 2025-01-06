<?php

require '../core/Router.php';
require '../app/controllers/UserController.php';
require '../app/controllers/CategoryController.php';
require '../app/controllers/DepartmentController.php';
require '../app/controllers/ProductController.php';
require '../app/controllers/MovementController.php';
require '../app/controllers/MovementTypeController.php';

$url = $_SERVER['QUERY_STRING'];
// echo 'URL = ' . $url . '<br>';

$content = file_get_contents("php://input");
$router = new Router();

/***************************** UserController ****************************/
$router->add('/public/user/get', array(
    'controller' => 'UserController',
    'action' => 'getAllUsers'
));

$router->add('/public/user/get/{id}', array(
    'controller' => 'UserController',
    'action' => 'getUserById'
));

$router->add('/public/user/create', array(
    'controller' => 'UserController',
    'action' => 'createUser'
));

$router->add('/public/user/update/{id}', array(
    'controller' => 'UserController',
    'action' => 'updateUser'
));

$router->add('/public/user/delete/{id}', array(
    'controller' => 'UserController',
    'action' => 'deleteUser'
));


/***************************** Department ****************************/
$router->add('/public/department/get', array(
    'controller' => 'DepartmentController',
    'action' => 'getAllDepartments'
));

$router->add('/public/department/get/{id}', array(
    'controller' => 'DepartmentController',
    'action' => 'getDepartmentById'
));

$router->add('/public/department/create', array(
    'controller' => 'DepartmentController',
    'action' => 'createDepartment'
));

$router->add('/public/department/update/{id}', array(
    'controller' => 'DepartmentController',
    'action' => 'updateDepartment'
));

$router->add('/public/department/delete/{id}', array(
    'controller' => 'DepartmentController',
    'action' => 'deleteDepartment'
));

/***************************** Product ****************************/
$router->add('/public/product/get', array(
    'controller' => 'ProductController',
    'action' => 'getAllProducts'
));

$router->add('/public/product/get/{id}', array(
    'controller' => 'ProductController',
    'action' => 'getProductById'
));

$router->add('/public/product/create', array(
    'controller' => 'ProductController',
    'action' => 'createProduct'
));

$router->add('/public/product/update/{id}', array(
    'controller' => 'ProductController',
    'action' => 'updateProduct'
));

$router->add('/public/product/delete/{id}', array(
    'controller' => 'ProductController',
    'action' => 'deleteProduct'
));

/***************************** Product Category ****************************/
$router->add('/public/category/get', array(
    'controller' => 'CategoryController',
    'action' => 'getAllCategories'
));

$router->add('/public/category/get/{id}', array(
    'controller' => 'CategoryController',
    'action' => 'getCategoryById'
));

$router->add('/public/category/create', array(
    'controller' => 'CategoryController',
    'action' => 'createCategory'
));

$router->add('/public/category/update/{id}', array(
    'controller' => 'CategoryController',
    'action' => 'updateCategory'
));

$router->add('/public/category/delete/{id}', array(
    'controller' => 'CategoryController',
    'action' => 'deleteCategory'
));

/***************************** Movement ****************************/
$router->add('/public/movement/get', array(
    'controller' => 'MovementController',
    'action' => 'getAllMovements'
));

$router->add('/public/movement/get/{id}', array(
    'controller' => 'MovementController',
    'action' => 'getMovementById'
));
$router->add('/public/movement/get/filtered', array(
    'controller' => 'MovementController',
    'action' => 'getMovementByData'
));

$router->add('/public/movement/sale', array(
    'controller' => 'MovementController',
    'action' => 'sale'
));
$router->add('/public/movement/purchase', array(
    'controller' => 'MovementController',
    'action' => 'purchase'
));
$router->add('/public/movement/inventoryTransfer', array(
    'controller' => 'MovementController',
    'action' => 'inventoryTransfer'
));

/***************************** Movement type ****************************/
$router->add('/public/movementType/get', array(
    'controller' => 'MovementTypeController',
    'action' => 'getAllMovementTypes'
));

$router->add('/public/movementType/get/{id}', array(
    'controller' => 'MovementTypeController',
    'action' => 'getMovementTypeById'
));

$router->add('/public/movementType/create', array(
    'controller' => 'MovementTypeController',
    'action' => 'createMovementType'
));

$router->add('/public/movementType/update/{id}', array(
    'controller' => 'MovementTypeController',
    'action' => 'updateMovementType'
));

$router->add('/public/movementType/delete/{id}', array(
    'controller' => 'MovementTypeController',
    'action' => 'deleteMovementType'
));


$urlParams = explode('/', $url);

$urlArray = array(
    'HTTP' => $_SERVER['REQUEST_METHOD'],
    'path' => $url,
    'controller' => '',
    'action' => '',
    'params' => ''
);

if (!empty($urlParams[2])) {
    $urlArray['controller'] = ucwords($urlParams[2]);
    if (!empty($urlParams[3])) {
        $urlArray['action'] = $urlParams[3];
        if (!empty($urlParams[4])) {
            $urlArray['params'] = $urlParams[4];
        };
    } else {
        $urlArray['action'] = 'index';
    }
} else {
    $urlArray['controller'] = 'Home';
    $urlArray['action'] = 'index';
}

if ($router->matchRoute($urlArray)) {
    $method = $_SERVER['REQUEST_METHOD'];

    // Define los parámetros según el método HTTP
    $params = [];

    if ($method === 'GET') {
        $params[] = intval($urlArray['params']) ?? null;
    } elseif ($method === 'POST') {
        $json = file_get_contents('php://input');
        $params[] = json_decode($json, true);
    } elseif ($method === 'PUT') {
        $id = intval($urlArray['params']) ?? null;
        $json = file_get_contents('php://input');
        $params[] = $id;
        $params[] = json_decode($json, true);
    } elseif ($method === 'DELETE') {
        $params[] = intval($urlArray['params']) ?? null;
    }

    $controller = $router->getParams()['controller'];
    $action = $router->getParams()['action'];
    $controller = new $controller();

    if (method_exists($controller, $action)) {
        $resp = call_user_func_array([$controller, $action], $params);
    } else {
        echo "El método no existe";
    }
} else {
    echo "URL no válida: error 404 Not Found \n" . $url;
}
