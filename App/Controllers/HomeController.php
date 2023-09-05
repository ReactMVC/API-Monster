<?php

namespace Monster\App\Controllers;

class HomeController
{
    public function index()
    {
        view("index", ['name' => 'Hossein']);
    }
}