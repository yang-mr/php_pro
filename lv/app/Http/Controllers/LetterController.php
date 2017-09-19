<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LetterController extends Controller
{
    public function __construct($value='')
    {
        $this->middleware(['middleware' => ['vip']]);
    }
}
