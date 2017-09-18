<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Gift;
use App\Model\UserGift;
use App\Model\Attention;

class GiftController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}

    public function index()
    {
			$data = Gift::paginate(12);
    		return view('gift.gift', ['data'=>$data, 'type'=>0]);
    }

    public function getGiftsFromType($type = 0)
    {
    	if ($type > -1) {
    		if ($type == 0) {
    			//全部礼物
    			$data = Gift::paginate(4);
    		} else {
                $data = Gift::where('type', $type)->paginate(4);
    		}

            foreach ($data as $gift) {
                $gift_id = $gift['id'];
                $user_id = auth()->user()->id;

                $tmpGift = UserGift::where('user_id', $user_id)
                            ->where('gift_id', $gift_id)
                            ->where('type', 0)
                            ->get();
               // exit;
                if (!empty($tmpGift->toArray())) {
                    $gift['collect'] = true;
                } else {
                    $gift['collect'] = false;
                }
            }

            //$data = ['gifts'=>$data];
            return view('gift.gift', ['data'=>$data, 'display' => true, 'type'=>$type]);
    	}
    }

    /**
     * @Author   jack_yang
     * @DateTime 2017-09-18T12:33:27+0800
     * @param    integer                  $gift_id [description]
     * @return   [int]                            [1:收藏成功 0:收藏失败]
     */
    public function collectGift($gift_id=0)
    {
        if ($gift_id != 0) {
                $user_id = auth()->user()->id;
                $userGift = new UserGift;
                $userGift->gift_id = $gift_id;
                $userGift->user_id = $user_id;
                $userGift->type = 0;
                if ($userGift->save()) {
                    return 1;
                }
        }
        return 0;
    }

    /**
     * @Author   jack_yang
     * @DateTime 2017-09-18T14:20:23+0800
     * @param    integer                  $type [0:我赠送的礼物; 1:我收到的礼物; 2:我收藏的礼物]
     * @return   [type]                         [description]
     */
    public function getGiftsByType($type = 0)
    {
        if ($type > -1) {
            $data = UserGift::where('type', $type)->paginate(4);
            foreach ($data as $gift) {
            //    var_dump($gift['gift_id']);
                $tmp = Gift::find($gift['gift_id'], ['title', 'price', 'img_url']);
                $gift['title'] = $tmp['title'];
                $gift['price'] = $tmp['price']; 
                $gift['img_url'] = $tmp['img_url'];
            }
            //$data = ['gifts'=>$data];
            return view('gift.gift', ['data'=>$data, 'type'=>$type + 4, 'display' => false]);
        }
    }

    public function getAttentions()
    {
        $id = auth()->user()->id;
        $users = Attention::where('user_id', $id)->paginate(4);
        foreach ($users as $user) {
            $tmpUser = User::find($user['id']);
            $user['name'] = $tmpUser['name'];
            $user['age'] = $tmpUser['birthday'];
        }
    }
}
