<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;

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
        return view('home', $data);
    }

    public function home()
    {
        $id = auth()->user()->id;
        $obj = User::all(['name', 'description', 'id'])->where('id', '!=', $id);
        $users = $obj->toArray();
       
        for ($i = 0; $i < count($users); $i++) {
            $user_id = $users[1]['id'];
            $avatar_result = DB::select('select img_url from imgs where user_id = :user_id and type = :type', [$user_id, 0]);
         //   $users[$i]['id'] = $user_id;
            if (!empty($avatar_result)) {
                  $img_avatar = $avatar_result[0]->img_url;
                  $users[1]['img_avatar'] = $img_avatar;
            } else {
                  $users[1]['img_avatar'] = asset('img/default_avatar.png');
            }
        }

        $data = array(
            'users' => $users,
            );
        return view('welcome', $data);
    }
}
