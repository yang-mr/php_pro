<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Model\Letter;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 登录验证规则
     * @return [type] [description]
     */
    protected static function rules()
    {
        return [
            'name' => 'required',
            'password' => 'required'
            ];
    }

    /**
     * @Author   jack_yang
     * @DateTime 2017-09-21T13:55:56+0800
     * @return   [type]                   [我发送的信件]
     */
    public function letters()
    {
        return $this->hasMany('App\Model\Letter', 'from_id');
    }

    /**
     * @Author   jack_yang
     * @DateTime 2017-09-21T13:59:46+0800
     * @return   [type]                   [我收到的信件]
     */
    public function toLetters()
    {
        return $this->hasMany('App\Model\Letter', 'to_id');
    }

    /**
     * @Author   jack_yang
     * @DateTime 2017-09-21T14:02:11+0800
     * @return   [type]                   [我关注的人]
     */
    public function attentions()
    {
        return $this->hasMany('App\Model\Attention');
    }

    /**
     * @Author   jack_yang
     * @DateTime 2017-09-21T14:02:26+0800
     * @return   [type]                   [谁关注了我]
     */
    public function toAttentions()
    {
        return $this->hasMany('App\Model\Attention', 'other_id');
    }

    /**
     * @Author   jack_yang
     * @DateTime 2017-09-21T14:03:32+0800
     * @return   [type]                   [我看过的人]
     */
    public function looks()
    {
        return $this->hasMany('App\Model\Look');
    }

    /**
     * @Author   jack_yang
     * @DateTime 2017-09-21T14:05:07+0800
     * @return   [type]                   [谁看过我]
     */
    public function toLooks()
    {
        return $this->hasMany('App\Model\Look', 'other_id');
    }

     /**
     * @Author   jack_yang
     * @DateTime 2017-09-21T14:03:32+0800
     * @return   [type]                   [我送出的礼物]
     */
    public function gifts()
    {
        return $this->hasMany('App\Model\UserGift');
    }

    /**
     * @Author   jack_yang
     * @DateTime 2017-09-21T14:05:07+0800
     * @return   [type]                   [我收到的礼物]
     */
    public function toGifts()
    {
        return $this->hasMany('App\Model\UserGift', 'otheruser_id');
    }
}
