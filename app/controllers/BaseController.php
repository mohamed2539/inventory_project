<?php
class BaseController {
    protected function jsonResponse($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    protected function validateRequest($method) {
        if ($_SERVER['REQUEST_METHOD'] !== strtoupper($method)) {
            $this->jsonResponse(['error' => 'Invalid Request Method'], 405);
        }
    }

    protected function redirect($url) {
        header("Location: $url");
        exit();
    }
}
?>
