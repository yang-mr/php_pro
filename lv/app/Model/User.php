<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use App\Agecope;

class User extends Model
{
	//use SoftDeletes;
	//public $dates = ['deleted_at']; //软删除

	protected $hidden = ['password'];

	protected $appends = ['is_admin'];

	public static function boot() {
		parent::boot();
		// static::addGlobalScope(new Agecope);
		// static::addGlobalScope('username', function(Builder $builder) {
		// 	$builder->where('username', '=', 'rose');
		// });
	}

	protected $events = [
		'saved' => Lv_userSaved::class,
		'deleted' =>Lv_userDeleted::class
	];

	public function getusernameAttribute($value) {
		return 'sb ' .  $value;
	}

	public function setUsernameAttribute($value) {
		$this->attributes['username'] = $value . 'jdkj';
	}

	public function getIsAdminAttribute() {
		return '222';
	}

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

}
