<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\User;
use App\Model\LetterModel;
use App\Model\Letter;
use App\Model\InLetter;
use App\Model\OutLetter;
use App\Model\Img;
use App\Events\AttentionEvent;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendEmail;

class LetterController extends Controller
{
    public function __construct($value='')
    {
        $this->middleware('auth');
    }

    public function index($id = 0)
    {
    	if ($id != 0) {
    		$user = User::find($id, ['id', 'name', 'sex', 'city', 'area', 'avatar_id']);
            $user['avatar_url'] = Img::find($user['avatar_id']);
            $model = LetterModel::where('type', $user['sex'])->get(['content'])->toArray();
            //var_dump(['user' => $user, 'models' => $model]);
            
    		return view('letter.write_letter', ['user' => $user, 'models' => $model]);
    	}
    }

    public function insertLetter(Request $request)
    {
        $from_id = auth()->user()->id;
        $to_id = $request->input('id');
        $content = $request->input('letter_content');

        $result = 0;

        if (empty($content)) {
            $result = 2;
        }

      //  var_dump($to_id);

       $result = DB::transaction(function () use ($from_id, $to_id, $content) {
       // var_dump($to_id);
                $letter = new Letter;
                $letter->content = $content;
                $letter->save();
                $letter_id = $letter->id;

                $inLetter = new InLetter;
                $inLetter->user_id = $from_id;
                $inLetter->letter_id = $letter_id;
                $inLetter->save();

                $outLetter = new OutLetter;
                $outLetter->user_id = $to_id;
                $outLetter->letter_id = $letter_id;
                $outLetter->save();

               // InLetter::create(['user_id' => $from_id, 'letter_id' => $letter_id]);
               // OutLetter::create(['user_id' => $to_id, 'letter_id' => $letter_id]);
                return 1;
        });
       
        //发送通知
        if ($result == 1) {
           //sendMsg($to_id);
            $toUser = User::find($to_id, ['name']);
            $msg = $toUser['name'] . " 给你发信啦！";
            dispatch(new SendEmail($to_id, $msg));
        } else if ($result != 1 || $result != 2) {
            $result = 0;
        }
        return $result;
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
        $letters = $user->inLetters;
         foreach ($letters as $letter) {
            $letter_id = $letter['letter_id'];
            $outLetter = OutLetter::where('letter_id', $letter_id)->get();
            $outUser = User::find($outLetter[0]['user_id'], ['id', 'name', 'city', 'avatar_url']);
            $outUser['status'] = $outLetter[0]['status'];
            $letter['user'] = $outUser;
        }
        return view('letter.box_letter', ['letters'=>$letters, 'type' => 0]);
    }

    public function out_box()
    {
        $user = auth()->user();
        $letters = $user->outLetters;
        foreach ($letters as $letter) {
            $letter_id = $letter['letter_id'];
            $inLetter = InLetter::where('letter_id', $letter_id)->get();
            $outUser = User::find($inLetter[0]['user_id'], ['id', 'name', 'city', 'avatar_url']);
            $outUser['status'] = $inLetter[0]['status'];
            $letter['user'] = $outUser;
        }
        return view('letter.box_letter', ['letters'=>$letters, 'type' => 1]);
    }

    /**
     * @Author   jack_yang
     * @DateTime 2017-09-22T15:26:16+0800
     * @param    Request                  $request [description]
     * @return   [type]                            [查看自己发的信件]
     */
    public function look_letter($letter_id = 0)
    {
        $letter_content = Letter::find($letter_id, ['content']);
        if ($letter_content) {
            $letter_content['status'] = 1;
        } else {
            $letter_content['status'] = 0;
        }
        return json_encode($letter_content);
    }

     public function set_status($letter_id = 0)
    {
        $result = OutLetter::where('letter_id', $letter_id)->update(['status'=>1]);
        if ($result) {
            return 1;
        } else {
            return 0;
        }
    }
}
