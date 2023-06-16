<?php declare(strict_types=1);

abstract class Input {
    public static function post(string $field): mixed {
        $json = file_get_contents('php://input');

        if (!$json) return null;
        

        $data = json_decode($json, true);

        if ($data === null) {
            throw new Exception('Error decoding JSON data');
        }

        $_POST = array_merge($_POST ?: [], $data);

        return $_POST[$field] ?? null;
        
    }

    public static function get(string $field): ?string {
        return isset($_GET[$field]) ? $_GET[$field] : null;
    }
}
