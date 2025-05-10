<?php

namespace App\Http\Controllers;
use App\DataTables\UserDataTable;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('user.index');
    }
}
