<?php

namespace App\Http\Controllers;
use App\DataTables\CompanyDataTable;

use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(CompanyDataTable $dataTable)
    {
        return $dataTable->render('company.index');
    }
}
