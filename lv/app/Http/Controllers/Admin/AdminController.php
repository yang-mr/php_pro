<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Illuminate\Support\Facades\DB;

use App\Model\Vip;
use App\Model\Gift;

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
       // $this->middleware('checkAdmin');
    }

    public function logout() {
        Auth::logout();
        return Redirect::route('admin_login');
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
            if (Auth::user()->is_admin) {
                return Redirect::route('admin_center');
            } 
        }
        return Redirect::route('admin_login')
                ->withInput()
                ->withErrors(['name' => '用户名或者密码不正确，请重试！']);
      /*  var_dump($request->input('name'));
        exit;*/
       // return view('admin.login');
    }

/**
 * @Author   jack_yang
 * @DateTime 2017-09-12T15:59:37+0800
 * @return   [array]                   [所有用户信息]
 */
    public function adminCenter()
    {

        $users = User::where('is_admin', 0)->paginate(6);
        $data = array(
            'users' => $users,
            );
        //var_dump($data);
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

    /**
     * @Author   jack_yang
     * @DateTime 2017-09-15T11:26:33+0800
     * @return   [array]                [gift管理]
     */
    public function adminGifts()
    {
        $gifts = Gift::paginate(4);
        $data = array(
            'gifts' => $gifts,
            );
        //var_dump($data);
        return view('admin.admin_center', $data);
    }

    /**
     * @Author   jack_yang
     * @DateTime 2017-09-15T16:29:05+0800
     * 增加礼物
     * @param    Request                  $request [description]
     */
    public function addGift(Request $request)
    {
        $result = [];
        $title = $request->input('title');
        $description = $request->input('description');
        $price = $request->input('price');
        $type = $request->input('type');

        if ($title == null || $description == null) {
            $result['status'] = '2';
            return json_encode($result);
        }
      
        $gift = new Gift;
        $gift->title = $title;
        $gift->description = $description;
        $gift->price = $price;
        $gift->type = $type;
        if ($gift->save()) {
            $result['status'] = '1';
        } else {
            $result['status'] = '0';
        }
        return json_encode($result);
    }

    /**
     * @Author   jack_yang
     * @DateTime 2017-09-15T16:29:51+0800
     * @param    string                   $value [description]
     * @return   [type]                          [description]
     */
    public function editGift(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255', 
        ]);
        $msg = [];
        $gift_id = $request->input('id');
        $title = $request->input('title');
        $description = $request->input('description');
        $price = $request->input('price');
        $type = $request->input('gifttype');
        $discount = $request->input('discount');

        $data = [   'title' => $title,
                    'description' => $description,
                    'price' => $price,
                    'type' => $type,
                    ];
        $result = Gift::where('id', $gift_id)
                ->update($data);
        if ($result) {
            $msg['status'] = 1;
        } else {
            $msg['status'] = 0;
        }
        return json_encode($msg);
    }
}
