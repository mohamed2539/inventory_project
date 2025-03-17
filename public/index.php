<?php


require_once '../app/controllers/BaseController.php';
require_once '../app/models/Branch.php';
require_once '../app/models/Material.php';
require_once '../app/models/User.php';
require_once '../app/models/Supplier.php';
require_once '../app/models/Transaction.php';
require_once '../app/controllers/BranchController.php';
require_once '../app/controllers/SupplierController.php';
require_once '../app/controllers/MaterialController.php';
require_once '../app/controllers/TransactionController.php';//transaction

$controllerName = $_GET['controller'] ?? null;
$action = $_GET['action'] ?? null;

if (!$controllerName || !$action) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid Request"]);
    exit();
}

$controller = null;

if ($controllerName === 'branch') {
    $controller = new BranchController();
} elseif ($controllerName === 'supplier') {
    $controller = new SupplierController();
} elseif ($controllerName === 'material') {
    $controller = new MaterialController();
} elseif ($controllerName === 'transaction') {
    $controller = new TransactionController();
} else {
    http_response_code(400);
    echo json_encode(["error" => "Invalid Controller"]);
    exit();
}

if (!method_exists($controller, $action)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid Action"]);
    exit();
}

$controller->$action();


