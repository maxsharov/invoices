<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Secret;
use App\Models\Item;

class SecretController extends Controller
{
    public function index() {
        return $request->user()->items;
    }
}
