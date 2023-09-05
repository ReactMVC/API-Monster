<?php

/*
Application helpers
*/

// Load Thems
function view($path, $data = [])
{
    // Replace all . to /
    $path = str_replace('.', '/', $path);

    extract($data);

    // include views folder path
    $viewPath = 'views/' . $path . '.php';

    include_once $viewPath;
}