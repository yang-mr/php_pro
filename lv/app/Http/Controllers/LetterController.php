<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\User;
use App\Model\LetterModel;
use App\Model\Letter;
use App\Events\AttentionEvent;

class LetterController extends Controller
{
    public function __construct($value='')
    {
        //$this->middleware(['middleware' => ['vip']]);
    }

    public function index($id = 0)
    {
    	if ($id != 0) {
    		$user = User::find($id, ['id', 'name', 'sex', 'city', 'area', 'avatar_url']);
            $model = LetterModel::all(['content'])->toArray();
            //var_dump(['user' => $user, 'models' => $model]);
            
    		return view('letter.write_letter', ['user' => $user, 'models' => $model]);
    	}
    }

    public function insertLetter(Request $request)
    {
        $from_id = auth()->user()->id;
        $to_id = $request->input('id');

        $letter = new Letter();
        $letter->from_id = $from_id;
        $letter->to_id = $to_id;
        $letter->content = $request->input('letter_content');
        if ($letter->save()) {
            //写信成功 发送通知
            event(new AttentionEvent(1, $to_id));
            return 1;
        } else {
            //写信失败
            return 0;
        }
    }
}
