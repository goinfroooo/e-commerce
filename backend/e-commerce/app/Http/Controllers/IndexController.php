<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;


class IndexController extends Controller
{
    public function index()
    {
        return view('welcome'); // 'welcome' est le nom de votre vue d'accueil
    }
}
