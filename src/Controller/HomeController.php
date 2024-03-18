<?php

namespace App\Controller;

use Framework\Http\Request;
use Framework\View;

class HomeController
{
    public function index(Request $request): string
    {
        return View::make('Home/index', ['nome' => 'Gabriel']);
    }
}
