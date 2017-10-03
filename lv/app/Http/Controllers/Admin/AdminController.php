<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Model\Vip;
use App\Model\Gift;
use App\Model\Oneself;
use App\Model\Img;

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
        $type = $request->input('gifttype');

       /* if ($title == null || $description == null) {
            $result['status'] = '2';
            return json_encode($result);
        }*/

        $file = $request->file('img_url');
        if($file->isValid()){  
            //获取原文件名  
            $originalName = $file->getClientOriginalName();  
            //扩展名  
            $ext = $file->getClientOriginalExtension();  
            //文件类型  
            //$type = $file->getClientMimeType();  
            //临时绝对路径  
            $realPath = $file->getRealPath();  
           // var_dump($realPath);

            $disk = Storage::disk('qiniu');
            $filename = getUploadFileName().uniqid().'.'.$ext;
            $result_upload = $disk->put($filename, file_get_contents($realPath));
            if ($result_upload) {
                $gift = new Gift;
                $gift->title = $title;
                $gift->description = $description;
                $gift->price = $price;
                $gift->type = $type;
                $gift->img_url = config('app.qiniu_domain') . $filename;
                if ($gift->save()) {
                    $result['status'] = '1';
                } else {
                    $result['status'] = '0';
                }
            } else {
                //文件上传失败
                $result['status'] = '3';
            }
         } else {
             //文件上传失败
                $result['status'] = '3';
         }
         //判断文件是否上传成功  
       
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

    public function checkOneselfs()
    {

        $oneselfs = Oneself::where('status', 2)->paginate(12);
        foreach ($oneselfs as $oneself) {
            $user = User::find($oneself['user_id']);
            $oneself['name'] = $user->name;
            $oneself['sex'] = $user->sex;
        }
        $result['oneselfs'] = $oneselfs;
        //var_dump($result);
        return view('admin.admin_center', $result);
    }

    public function checkImgs()
    {
        $imgs = Img::where('status', 2)->paginate(12);
        foreach ($imgs as $oneself) {
            $user = User::find($oneself['user_id']);
            $oneself['name'] = $user->name;
            $oneself['sex'] = $user->sex;
        }
        $result['imgs'] = $imgs;
        //var_dump($result);
        return view('admin.admin_center', $result);
    }

    public function operateOneself($type = 0, $id = 0)
    {
        $result['status'] = 0;
        if ($type == 1) {
            //设置为通过
            $tmp = Oneself::where('id', $id)->update(['status' => 1]);
            if ($tmp) {
                $result['status'] = 1;
            } else {
                $result['status'] = 0;
            }
        } else if ($type == 2) {
            //设置为不通过
            $tmp = Oneself::where('id', $id)->update(['status' => 0]);
            if ($tmp) {
                $result['status'] = 1;
            } else {
                $result['status'] = 0;
            }
        }
        //var_dump($result);
        return json_encode($result);
    }

    public function operateImg($type = 0, $id = 0)
    {
        $result['status'] = 0;
        if ($type == 1) {
            //设置为通过
            $tmp = Img::where('id', $id)->update(['status' => 1]);
            if ($tmp) {
                $result['status'] = 1;
            } else {
                $result['status'] = 0;
            }
        } else if ($type == 2) {
            //设置为不通过
            $tmp = Img::where('id', $id)->update(['status' => 0]);
            if ($tmp) {
                $result['status'] = 1;
            } else {
                $result['status'] = 0;
            }
        }
        //var_dump($result);
        return json_encode($result);
    }

    public function getOneselfs($status = 0)
    {
        if ($status == 0 || $status == 1 || $status == 2) {
            $oneselfs = Oneself::where('status', $status)->paginate(12);
            foreach ($oneselfs as $oneself) {
                $user = User::find($oneself['user_id']);
                $oneself['name'] = $user->name;
                $oneself['sex'] = $user->sex;
                $oneself['avatar_url'] = Img::where('user_id', $user->id)->where('type', '0')->where('status', 1)->first(['img_url']);
            }
            return json_encode($oneselfs);
        }
    }

    public function getImgs($status = 0)
    {
        if ($status == 0 || $status == 1 || $status == 2) {
            $imgs = Img::where('status', $status)->paginate(12);
            foreach ($imgs as $oneself) {
                $user = User::find($oneself['user_id']);
                $oneself['name'] = $user->name;
                $oneself['sex'] = $user->sex;
                $oneself['avatar_url'] = Img::where('user_id', $user->id)->where('type', '0')->where('status', 1)->first(['img_url']);
            }
            return json_encode($imgs);
        }
    }
}
