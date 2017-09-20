<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\User;
use App\Model\LetterModel;

class LetterController extends Controller
{
    public function __construct($value='')
    {
        //$this->middleware(['middleware' => ['vip']]);
    }

    public function index($id = 0)
    {
    	if ($id != 0) {
    		$user = User::find($id, ['name', 'sex', 'city', 'area', 'avatar_url']);
            $model = LetterModel::all(['content'])->toArray();
            var_dump(['user' => $user, 'models' => $model]);
            
    		return view('letter.write_letter', ['user' => $user, 'models' => $model]);
    	}
    }

    public function insertLetter(Request $request)
    {
        
    }
}
