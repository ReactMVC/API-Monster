<?php

namespace Monster\App\Controllers;

use Monster\App\Models\Json;

class AppController
{
    public function index()
    {
        $json = new Json();

        $data = [
            "status" => true,
            "message" => "API Page"
        ];

        $json->clean($data, 200);
    }
}
