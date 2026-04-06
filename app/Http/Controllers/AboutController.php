<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController
{
    
    public function about()
    {
        return view('pages.about');
    }
}
