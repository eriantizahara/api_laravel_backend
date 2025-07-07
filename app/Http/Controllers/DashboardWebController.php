<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardWebController extends Controller
{
    public function index()
    {
        return view('layouts.formdashboard ');
    }
}
