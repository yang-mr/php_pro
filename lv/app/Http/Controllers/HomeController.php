<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Model\Attention;
use App\Model\oneself;
use App\Model\Img;
use App\Model\Look;
use Redirect;

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
        $data['toLetters'] = $user->outLetters;

        //我收到的礼物
        $data['toGifts'] = $user->toGifts;

     //   var_dump($data['toMyLooks']);
        return view('home', $data);
    }

    /*
        用户基本资料
    */
    public function baseMeans()
    {
        $user = auth()->user();
      // var_dump($user->toArray());
        return view('home.user_msg', $user);
    }

   /**
    * @Author   jack_yang
    * @DateTime 2017-09-25T14:06:36+0800
    *   height:130
        children:0
        work_location:0
        work_sublocation:1100
        home_location:0
        home_sublocation:0
        bloodtype:0
        nation:0
        income:0
        house:0
        auto:0
        true_name:
        id_card:
        qq:
        msn:
        address:
        postcode:
        share:1
    * @param    Request                  $request [编辑用户资料]
    * @return   [type]                            [description]
    */
    public function editMsg(Request $request)
    {   
          $resultData = [];
          $id = auth()->user()->id;
          $data = $request->except(['_token', 'change_area_reason', 'share', 'postcode', 'msn', 'address']);
            $result = User::where('id', $id)
                ->update($data);
            if ($result) {
                $resultData['status'] = 1;
                //return Redirect::route('edit_img');
            } else {    
                $resultData['status'] = 0;
            }
            return json_encode($resultData);
        //return view('home.user_msg');
    }

    public function editOneself(Request $request)
    {

          $resultData['status'] = 0;
          $content = $request->input('description');

          $resultData['status'] = DB::transaction(function () use ($content) {
                $id = auth()->user()->id;
                //软删除之前的
                Oneself::where('user_id', $id)->delete();
               
                //添加刚刚的
                $oneself = new Oneself;
                $oneself->description = $content;
                $oneself->user_id = $id;
                $oneself->save();
                return 1;
          });

          return json_encode($resultData);
    }

    /**
     * @Author   jack_yang
     * @DateTime 2017-09-25T14:46:02+0800
     * @param    Request                  $request [修改用户图片]
     * @return   [type]                            [description]
     */
    public function editImg(Request $request)
    {   
        $user = auth()->user(['score']);
        $id = $user->id;
        $user['user'] = Img::where('user_id', $id)->where('type', 0)->first(['status', 'img_url'])->toArray();
        $user['files'] = Img::where('user_id', $id)->where('type', 1)->paginate(6)->toArray();
       // var_dump($user);
        return view('home.user_img', $user);
    }

    public function uploadImg(Request $request)
    { 
            $id = auth()->user()->id;
            $type = $request->input('type');
            $files = $request->file('upload_file');
            $result['status'] = '1';
            $result['files'] = [];

            for ($i=0; $i < count($files); $i++) { 
                $file = $files[$i];
                if($file->isValid()){  
                //获取原文件名  
                $originalName = $file->getClientOriginalName();  
                //扩展名  
                $ext = $file->getClientOriginalExtension();  
                //文件类型  
                //$type = $file->getClientMimeType();  
                //临时绝对路径  
                $realPath = $file->getRealPath(); 

                $disk = Storage::disk('qiniu');
                $filename = getUploadFileName().uniqid().'.'.$ext;
                $result_upload = $disk->put($filename, file_get_contents($realPath));
                $realname = config('app.qiniu_domain') . $filename;
                $img = new Img;
                $img->user_id = $id;
                $img->type = $type;
                $img->img_url = $realname;

                $tmp = $img->save();
                if ($tmp) {
                   $imgs = Img::where('user_id', $id)->where('type', $type)->paginate(6);
                   $result['files'] = $imgs->toArray(); 
                } else {
                   $result['status'] = '0';
                }
               // $result['img_url'] = $realname;
            }
          }
           //var_dump(json_encode($result));
            return json_encode($result);
    }

    public function userSearch(Request $request)
    {   
        $result = [];
        $sex = $request->input('sex');
        $min_age = $request->input('min_age');
        $max_age = $request->input('max_age');
        $obj = User::whereBetween('age', [$min_age, $max_age])
                        ->where('sex', $sex)
                        ->where('id', '!=', auth()->user()->id)
                        ->get(['name', 'age', 'avatar_url', 'id']);
        if ($obj) {
            $result['status'] = 1;
            $user['users'] = $obj;
        } else {
            $result['status'] = 0;
        }

        return json_encode($user);
    }

    public function oneself()
    {
        $user = auth()->user();
        $hint = '';
        $oneself = Oneself::where('user_id', $user->id)->first(['description', 'status']);
        $user['description_status'] = 1;
        if (!empty($oneself)) {
              if ($oneself['status'] == 0) {
                  $hint = "[审核失败 请按要求重新填写~]" . $oneself['description'];
              } elseif ($oneself['status'] == 2) {
                  $hint = "[审核中~]" . $oneself['description'];
              } else if ($oneself['status'] == 1) {
                  $hint = $oneself['description'];
              }
            $user['description_status'] = $oneself['status'];
        }
      
        $user['description'] = $hint;
        return view('home.oneself', $user);
    }
}
