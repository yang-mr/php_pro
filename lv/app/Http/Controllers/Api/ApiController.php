<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends BaseApiController
{
    public function login(Request $request) {
        return $this->response->array(['status' => 1]);
    }
}
