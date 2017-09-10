<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use App\Agecope;

class Attention extends Model
{
	use SoftDeletes;
	public $dates = ['deleted_at']; //软删除

	public static function boot() {
		parent::boot();
		// static::addGlobalScope(new Agecope);
		// static::addGlobalScope('username', function(Builder $builder) {
		// 	$builder->where('username', '=', 'rose');
		// });
	}

	// protected $events = [
	// 	'saved' => Lv_userSaved::class,
	// 	'deleted' =>Lv_userDeleted::class
	// ];
}
