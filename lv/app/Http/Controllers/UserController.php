<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;

use Qiniu\Storage\UploadManager;
use Qiniu\Auth;

class UserController extends Controller
{

	public function __construct() {
		//$this->middleware('checkid:10')->only('invoke');
	    $this->middleware('auth');
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
    /**
     * @Author   jack_yang
     * @DateTime 2017-09-07
     * @param    Request    $request [description]
     * @return   [type]              [description]
     */
    public function createUser(Request $request) {
        $this->validate($request, [
                'username' => 'bail|required|max:255',
                'password' => 'bail|required'
            ]);
    }

    /**
     * @Author   jack_yang
     * @DateTime 2017-09-07T14:31:26+0800
     * @param    Request                  $request [description]
     * @return   [type]                            [description]
     */
    public function editUserMsg(Request $request) {
        $this->validate($request, [
                'description' => 'bail|required|max:255',
                'requist' => 'bail|required'
            ]);
        $id = auth()->user()->id;
        $user = User::find($id);
        $requist = $request->input('requist');
        $user->requist = $requist;
        $user->description = $request->input('description');
        if ($user->save()) {
             return redirect('/home');
        } else {
             return redirect()->back()
                ->withInput($request->only('requist', 'remember'))
                ->withErrors(array('error'=>'提交失败'));
        }
    }

    public function editAvatar(Request $request) {
        $file = @$_FILES['file']; 
        $id = auth()->user()->id;
        if ("" != $file) {
            $accessKey = config('app.qiniu_ak', 'Laravel');
            $secretKey = config('app.qiniu_sk', 'Laravel');
            $bucket = config('app.qiniu_bucket', 'test');
            $auth = new Auth($accessKey, $secretKey);
            
            // 生成上传Token
            $token = $auth->uploadToken($bucket);
            // 构建 UploadManager 对象
            $uploadMgr = new UploadManager();
            $uploadresult = $uploadMgr->putFile($token, 'lv_avatar' . mt_rand() . time() . '.' . $file['name'], $file['tmp_name']);
            $data = array(
                $id,
                $uploadresult[0]['key'],
                0,
                date('Y-m-d H:i:sa')
                );
            $result = DB::insert('insert into imgs(user_id, img_url, type, created_at) values (?, ?, ?, ?)', $data);
            if ($result) {
              return '上传成功';
            } else {
              return redirect()->back()
                ->withInput($request->only('file', 'remember'))
                ->withErrors(array('error'=>'提交失败'));
            }
      }
   }
}
