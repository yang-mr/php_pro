<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Illuminate\Support\Facades\DB;

use App\Model\Vip;
use Redirect;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkLogin(Request $request) {
        $this->validate($request, User::rules());
        $name = $request->input('name');
        $password = $request->input('password');

        if (Auth::attempt(['name' => $name, 'password' => $password], $request->get('remember'))) {
            if (!Auth::user()->is_admin) {
                return Redirect::route('admin_center');
            } 
        }
        return Redirect::route('admin_login')
                ->withInput()
                ->withErrors('用户名或者密码不正确，请重试！');
        var_dump($request->input('name'));
        exit;
        return view('admin.login');
    }

/**
 * @Author   jack_yang
 * @DateTime 2017-09-12T15:59:37+0800
 * @return   [array]                   [所有用户信息]
 */
    public function adminCenter()
    {

        $users = User::where('is_admin', 0)->get(['id', 'name', 'email'])->toArray();
        $data = array(
            'users' => $users,
            );
        return view('admin.admin_center', $data);
    }

    /**
     * @Author   jack_yang
     * @DateTime 2017-09-12T17:08:23+0800
     * @return   [array]                   [所有的vip服务]
     */
    public function adminVips()
    {
        $vips = Vip::paginate(2);
        $data = array(
            'vips' => $vips,
            );
        //var_dump($data);
        return view('admin.admin_center', $data);
    }
}
