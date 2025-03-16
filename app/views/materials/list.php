<?php
require_once __DIR__ . '/../../app/models/Material.php';

$materialModel = new Material();
$materials = $materialModel->getAllMaterials();

echo json_encode($materials);
?>
