<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends BaseApiController
{
    public function login(Request $request) {
        return $this->response->array(['status' => 1]);
    }
}
