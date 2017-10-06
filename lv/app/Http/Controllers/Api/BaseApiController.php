<?php
	namespace App\Http\Controllers\Api;

	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use Dingo\Api\Routing\Helpers;
	use App\Http\Requests;
	 
	class BaseController extends Controller
	{
	    use Helpers;
	}