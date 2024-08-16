<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Logistica;
use App\Aduana;


class AduanaController extends Controller
{
    public function index()
    {
        $aduana = Aduana::all();
    }
}
