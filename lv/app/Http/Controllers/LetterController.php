<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\User;
use App\Model\LetterModel;
use App\Model\Letter;
use App\Model\InLetter;
use App\Model\OutLetter;
use App\Events\AttentionEvent;
use Illuminate\Support\Facades\DB;

class LetterController extends Controller
{
    public function __construct($value='')
    {
        $this->middleware('auth');
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
        $content = $request->input('letter_content');

        DB::transaction(function () use($from_id, $to_id, $content) {
                    $letter = new Letter;
                    $letter->content = $content;
                    $letter->save();
                    $letter_id = $letter->id;

                    InLetter::create(['user_id' => $from_id, 'letter_id' => $letter_id]);
                    OutLetter::create(['user_id' => $to_id, 'letter_id' => $letter_id]);
                    return 1;
        });
        return 0;
       /* $letter = new Letter();
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
        }*/
    }

    public function in_box()
    {
        $user = auth()->user();
        $letters = $user->letters;
        $toLetters = $user->toLetters;
        var_dump($letters->toArray());
        exit();
    }

    public function out_box()
    {
        $user = auth()->user();
        $letters = $user->letters;
        foreach ($letters as $letter) {
            $id = $letter['other_id'];
            $letter['user'] = User::find($id, ['name', 'city']);
        }
        var_dump($letters->toArray());
        return view('letter.box_letter', ['letters'=>$letters]);
    }
}
