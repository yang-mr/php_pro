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

        $result = DB::select('select img_url from imgs where user_id = :user_id and type = :type', ['user_id' => $id, 'type' => 0]);
        if (!empty($result)) {
            $domain = config('app.qiniu_domain', 'Laravel');
            $avatar_url = $domain . $result[0]->img_url;
        } else {
          $avatar_url = asset('img/default_avatar.png');
        }
        $data = array(
            'user_description' => $user->description,
            'user_avatar' => $avatar_url,
            );
        //var_dump($data);

        //我关注的
        $myAttentions = Attention::where('user_id', $id)->get(['other_id']);
        $userData = [];
        for($i = 0; $i < count($myAttentions); $i++) {
           $user_id = $myAttentions[$i]['other_id'];
           $user = User::find($user_id, ['id', 'name'])->toArray();
           $img_result = DB::select('select img_url from imgs where user_id = :user_id and type = 0', [$user_id]);
           if (empty($img_result)) {
            $user['img_avatar'] = asset('img/default_avatar.png');
           } else {
            $user['img_avatar'] = $img_result['img_url'];
           }
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
           $img_result = DB::select('select img_url from imgs where user_id = :user_id and type = 0', [$user_id]);
           if (empty($img_result)) {
            $user['img_avatar'] = asset('img/default_avatar.png');
           } else {
            $user['img_avatar'] = $img_result['img_url'];
           }
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
        $id = auth()->user()->id;
        $obj = User::all(['name', 'description', 'id'])->where('id', '!=', $id);
        $users = $obj->toArray();
       
        for ($i = 1; $i <= count($users); $i++) {
            $user_id = $users[$i]['id'];
            $avatar_result = DB::select('select img_url from imgs where user_id = :user_id and type = :type', [$user_id, 0]);
         //   $users[$i]['id'] = $user_id;
            if (!empty($avatar_result)) {
                  $img_avatar = $avatar_result[0]->img_url;
                  $users[$i]['img_avatar'] = $img_avatar;
            } else {
                  $users[$i]['img_avatar'] = asset('img/default_avatar.png');
            }
        }

        $data = array(
            'users' => $users,
            );
        var_dump($data);
    //    exit();
        return view('welcome', $data);
    }
}
