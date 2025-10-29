<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index');
    }
}