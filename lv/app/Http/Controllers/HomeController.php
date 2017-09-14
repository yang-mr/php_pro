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
        $myAttentions = Attention::where('user_id', $id)->get(['other_id']);
        $userData = [];
        for($i = 0; $i < count($myAttentions); $i++) {
           $user_id = $myAttentions[$i]['other_id'];
           $user = User::find($user_id, ['id', 'name'])->toArray();
           $userData[$i] = $user;
        }
        $data['myAttentions'] = $userData;
      //  var_dump($data);
        //我看过的人
         $myLooks = Look::where('user_id', $id)->get(['other_id']);
        $userData = [];
        for($i = 0; $i < count($myLooks); $i++) {
           $user_id = $myLooks[$i]['other_id'];
           $user = User::find($user_id, ['id', 'name'])->toArray();
           $userData[$i] = $user;
        }
        $data['myLooks'] = $userData;
        var_dump($data);
        //谁看过我
        //谁关注过我
        return view('home', $data);
    }

    public function home()
    {
      
    }
}
