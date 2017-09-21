<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;

use App\Model\Attention;
use App\Model\Look;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = auth()->user()->id;
        $user = User::find($id);
        $data = array(
            'user_description' => $user->description,
            );
        //我关注的
      /*  $myAttentions = Attention::where('user_id', $id)->get(['other_id']);
        $userData = [];
        for($i = 0; $i < count($myAttentions); $i++) {
           $user_id = $myAttentions[$i]['other_id'];
           $user = User::find($user_id, ['id', 'name'])->toArray();
           $userData[$i] = $user;
        }*/
        $data['myAttentions'] = $user->attentions;
      //  var_dump($data);
        //我看过的人
        /* $myLooks = Look::where('user_id', $id)->get(['other_id']);
        $userData = [];
        for($i = 0; $i < count($myLooks); $i++) {
           $user_id = $myLooks[$i]['other_id'];
           $user = User::find($user_id, ['id', 'name'])->toArray();
           $userData[$i] = $user;
        }*/

        //谁看过我
        $toLooks = $user->toLooks;
        $userData = [];
        for($i = 0; $i < count($toLooks); $i++) {
           $tmpuser = User::find($toLooks[$i]['user_id'], ['id', 'name', 'city'])->toArray();
           $userData[$i] = $tmpuser;
        }
        $data['toMyLooks'] = $userData;

         //谁关注了我
        $toLooks = $user->toAttentions;
        $userData = [];
        for($i = 0; $i < count($toLooks); $i++) {
           $tmpuser = User::find($toLooks[$i]['user_id'], ['id', 'name', 'city'])->toArray();
           $userData[$i] = $tmpuser;
        }
        $data['toMyAttentions'] = $userData;

        //我收到的信件
        $data['toLetters'] = $user->toLetters;

        //我收到的礼物
        $data['toGifts'] = $user->toGifts;

     //   var_dump($data['toMyLooks']);
        return view('home', $data);
    }

    public function home()
    {
      
    }
}
