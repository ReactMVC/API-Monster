<?php

/*
Application helpers
*/

// Load Thems
function view($path, $data = [], $javascript = false)
{
    // Replace all . with /
    $path = str_replace('.', '/', $path);

    extract($data);

    // include views folder path
    $viewPath = 'views/' . $path . '.php';

    // Wrap the view rendering code in a buffer
    ob_start();
    include_once $viewPath;
    $viewContent = ob_get_clean();

    if ($javascript == "true") {
        $viewContent = str_replace('<title>', "<script>let monster = JSON.parse('" . json_encode($data) . "');</script>\n<title>", $viewContent);
    }

    echo $viewContent;
    exit();
}