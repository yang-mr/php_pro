<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class InLetter extends Model
{
    public $table = 'inletters';

    /**
     * @Author   jack_yang
     * @DateTime 2017-09-22T10:58:21+0800
     * @return   [type]                   [description]
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
