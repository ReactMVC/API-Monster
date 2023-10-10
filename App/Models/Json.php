<?php

namespace Monster\App\Models;

class Json
{
    // Clean and output the given data as JSON with indentation and Unicode and slashes escaping
    public static function clean(array $data, int $code = 200)
    {
        http_response_code($code); // HTTP Code
        header('Content-type: application/json; charset=utf-8'); // Set the response header to indicate JSON content type
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); // Encode the data as JSON with pretty print and Unicode and slashes escaping
    }

    // Output the given data as JSON without any special formatting
    public static function show(array $data, int $code = 200)
    {
        http_response_code($code); // HTTP Code
        header('Content-type: application/json; charset=utf-8'); // Set the response header to indicate JSON content type
        echo json_encode($data); // Encode the data as JSON
    }
}
