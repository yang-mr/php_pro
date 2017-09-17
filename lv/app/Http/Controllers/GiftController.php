<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Gift;

class GiftController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}

    public function index()
    {
			$data = Gift::paginate(12);
    		return view('gift.gift', ['gifts'=>$data]);
    }

    public function getGiftsFromType($type = 0)
    {
    	if ($type > -1) {
    		if ($type == 0) {
    			//全部礼物
    			$data = Gift::paginate(12);
    		} else if ($type == 1) {
    		}
    		return json_encode(['gifts'=>$data]);
    	}
    }
}
