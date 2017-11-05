<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\RsaKey;

class RsaKeyController extends Controller
{
    //

    public function index()
    {
        $keys = \App\RsaKey::all();
        return view('keys.index')->with( compact('keys'));

    }

    public function create()
    {
        return view('keys.create');

    }
}
