<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\User;

class UserController extends Controller
{

	public function __construct() {
		$this->middleware('checkid:10')->only('invoke');
	}
    //
    public function show(Request $request) {
           // $result = DB::select('select * from lv_user where user_id = ?', [1]);
           //  var_dump($result);

            // $result = DB::insert('insert into lv_users(username, password) values(?,?)', ['jack','kfjdkj']);
            // var_dump($result);

            // $result = DB::table('lv_users')->get();
            // var_dump($result);

            // $lv_user = Lv_user::withTrashed()
            //                 ->get();
            // var_dump($lv_user);

         //Lv_user::destroy(5);
        $flight = User::find(11);
        var_dump($flight);
        // $flights = Lv_user::withTrashed()->get();
        // var_dump($flights);
        // $result =  Lv_user::withoutGlobalScope(Agecope::class)->get();
        // var_dump($result);

        // $users = Lv_user::where('username', 'rose')->get();
        // foreach ($users as $user) {
        //     var_dump($user->username);
        // }

        // $user = Lv_user::find(11);
        // $user->username = 'sb';
        // $user->save();
      //  var_dump($users);
    }

    public function __invoke($id) {
    	return $id;
    }

        //跳到注册界面
    public function register(Request $request) {
        var_dump($request);
        exit;
        return View('register');
    }

    //创建用户  bail: 没验证通过不会进行下一项验证
    public function createUser(Request $request) {
        $this->validate($request, [
                'username' => 'bail|required|max:255',
                'password' => 'bail|required'
            ]);
    }
}
