<?php

/*
Application helpers
*/

use Monster\App\Models\Env;

// Load Thems
function view($path, $data = [])
{
    // Replace all . to /
    $path = str_replace('.', '/', $path);

    extract($data);

    // include views folder path
    $viewPath = 'views/' . $path . '.php';

    $env = new Env('.env');
    $javascript = $env->get("JAVASCRIPT_DATA");

    if ($javascript == "true") {
        echo "<script>let monster = JSON.parse('" . json_encode($data) . "')</script>";
    }

    include_once $viewPath;
}
