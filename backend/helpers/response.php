<?php
// Helper para respuestas JSON consistentes

function json_success($data = [], $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array_merge(['success' => true], $data));
    exit;
}

function json_error($message, $code = 400, $extra = []) {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array_merge([
        'success' => false,
        'message' => $message
    ], $extra));
    exit;
}
