<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * 信箱 from_id: 发件人id; to_id: 收件人id
 */
class Letter extends Model
{
    //   /**
    //  * @Author   jack_yang
    //  * @DateTime 2017-09-22T10:58:21+0800
    //  * @return   [type]                   [所属的发件箱]
    //  */
    // public function in()
    // {
    //     return $this->belongsTo('App\Model\InLetter');
    // }

    //   /**
    //  * @Author   jack_yang
    //  * @DateTime 2017-09-22T10:58:21+0800
    //  * @return   [type]                   [所属的收件箱]
    //  */
    // public function out()
    // {
    //     return $this->belongsTo('App\Model\OutLetter');
    // }
}
