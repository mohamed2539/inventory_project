require_once 'app/controllers/BranchController.php';
require_once 'app/controllers/SupplierController.php';
require_once 'app/controllers/MaterialController.php';

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


/*require_once 'app/controllers/MaterialController.php';
require_once 'app/controllers/SupplierController.php';
require_once 'app/controllers/BranchController.php';

$controller = null;
$action = $_GET['action'] ?? null;
$entity = $_GET['entity'] ?? null;

if ($entity === 'material') {
$controller = new MaterialController();
} elseif ($entity === 'supplier') {
$controller = new SupplierController();
} elseif ($entity === 'branch') {
$controller = new BranchController();
} else {
http_response_code(400);
echo json_encode(["error" => "Invalid Entity"]);
exit();
}

if ($action) {
if ($action === 'add') {
$controller->add();
} elseif ($action === 'get') {
$controller->get();
} elseif ($action === 'edit') {
$controller->edit();
} elseif ($action === 'delete') {
$controller->delete();
} else {
http_response_code(400);
echo json_encode(["error" => "Invalid Action"]);
}
}
*/?>
