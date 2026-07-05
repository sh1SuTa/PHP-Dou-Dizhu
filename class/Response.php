<?php
class Response
{
    public static function success($data = null): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'code' => 200,
            'message' => 'success',
            'data' => $data
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function error(int $code, string $message): void
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'code' => $code,
            'message' => $message
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}