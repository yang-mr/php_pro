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
        var_dump($result[0]->img_url);
        $domain = config('app.qiniu_domain', 'Laravel');
        $avatar_url = $domain . $result[0]->img_url;
        $data = array(
            'user_description' => $user->description,
            'user_avatar' => $avatar_url,
            );
        return view('home', $data);
    }

    public function home()
    {
        $obj = User::all(['name', 'description', 'id']);
        $users = $obj->toArray();

        for ($i = 0; $i < count($users); $i++) {
            $user_id = $users[$i]['id'];
            $avatar_result = DB::select('select img_url from imgs where user_id = :user_id and type = :type', [$user_id, 0]);
            if (!empty($avatar_result)) {

                  $img_avatar = $avatar_result[0]->img_url;
                  $users[$i]['img_avatar'] = $img_avatar;
            }
        }

        $data = array(
            'users' => $users,
            );
        return view('welcome', $data);
    }
}
