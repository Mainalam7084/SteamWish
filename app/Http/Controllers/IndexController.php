<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController
{
    public function index() {
        $query = "counter";
        $controller = new \App\Http\Controllers\SearchController();
        $results = $controller->search($query);

        return view('index', compact('results', 'query'));
    }
}
