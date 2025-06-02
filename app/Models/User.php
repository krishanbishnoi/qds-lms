<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;
use Eloquent;

class User extends Eloquent implements
	AuthenticatableContract,
	AuthorizableContract,
	CanResetPasswordContract
{
	use Authenticatable, Authorizable, CanResetPassword, Notifiable;


	//protected $dates = ['deleted_at'];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */


	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'middle_name',
		'email',
		'password',
		'employee_id',
		'first_name',
		'fullname',
		'last_name',
		'lob',
		'circle',
		'gender',
		'poi',
		'mobile_number',
		'password',
		'region',
		'user_role_id',
		'validate_string',
		'olms_id',
		'designation',
		'date_of_birth',
		'parent_id',
		'image',
		'ext_qa',
		'ext_qa_olms',
		'crm_id',
		'qms_id',
		'lms_access',
		'trainer_name',
		'trainer_olms',
		'location',
		'is_certified',
		'date_of_joining'
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];


	/* Scope Function
	 *
	 * @param null
	 *
	 * return query
	 */

	public function scopeActiveConditions($query)
	{
		return $query->where('is_active', 1)->where('is_verified', 1);
	} //end ScopeActiveCondition

	/**
	 * hasMany function for bind userLastLogin model
	 *
	 * @param null
	 *
	 * return query
	 */
	public function userLastLogin($query)
	{
		return $this->hasMany('App\Models\userLastLogin', 'user_id');
	} //end userLastLogin

	public function getImageAttribute($value = "")
	{
		if (!empty($value) && file_exists(USER_IMAGE_ROOT_PATH . $value)) {
			return USER_IMAGE_URL . $value;
		}
	}
	public function userDetails()
	{
		return $this->hasOne(UserDetail::class);
	}
	public function parentManager()
	{
		return $this->belongsTo(User::class, 'parent_id');
	}

	// 	public function userDetails()
	// {
	//     return $this->hasOne(UserDetail::class, 'user_id');
	// }
	// public function getIdentityFileAttribute($value = ""){
	// 	if(!empty($value) && file_exists(DRIVER_DOCUMENTS_IMAGE_ROOT_PATH.$value)){
	// 		return DRIVER_DOCUMENTS_IMAGE_URL.$value;
	// 	}
	// }
	// public function unreadNotifications()
	// {
	//     return $this->hasMany(Notification::class)->whereNull('read_at');
	// }

	public function participant()
	{
		return $this->hasOne(TestParticipants::class, 'trainee_id', 'id');
	}
}
