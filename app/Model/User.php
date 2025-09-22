<?php

namespace App\Model;

use Eloquent;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;

class User extends Eloquent implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
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
        'parent_id',
        'batch_id',
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
        'forgot_password_otp',
        'center',
        'region',
        'user_role_id',
        'validate_string',
        'olms_id',
        'designation',
        'date_of_birth',
        'ext_qa',
        'ext_qa_olms',
        'crm_id',
        'qms_id',
        'lms_access',
        'trainer_name',
        'trainer_olms',
        'location',
        'is_certified',
        'date_of_joining',
        'is_deleted',
        'deleted_at',
        'tl_name',
        'tl_olms',
        'am_name',
        'am_olms',
        'manager_name',
        'manager_olms'
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
        return $this->hasMany('App\Model\userLastLogin', 'user_id');
    } //end userLastLogin

    public function getImageAttribute($value = '')
    {
        if (!empty($value) && file_exists(config('USER_IMAGE_ROOT_PATH') . $value)) {
            return config('USER_IMAGE_URL') . $value;
        }
    }

    public function userDetails()
    {
        return $this->hasOne(UserDetail::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function designation()
    {
        return $this->hasOne(Designation::class, 'id', 'designation');
    }

    public function userlob()
    {
        return $this->belongsTo(Lob::class, 'lob', 'id'); // 'lob' is the foreign key
    }

    public function userbatch()
    {
        return $this->belongsTo(Batch::class, 'batch_id', 'id'); // 'center' is the foreign key
    }
    public function usercenter()
    {
        return $this->belongsTo(Center::class, 'center', 'id'); // 'center' is the foreign key
    }

    public function userdesignation()
    {
        return $this->belongsTo(Designation::class, 'designation', 'id'); // 'designation' is the foreign key
    }

    //     public function userDetails()
    // {
    //     return $this->hasOne(UserDetail::class, 'user_id');
    // }
    // public function getIdentityFileAttribute($value = ""){
    //     if(!empty($value) && file_exists(DRIVER_DOCUMENTS_IMAGE_ROOT_PATH.$value)){
    //         return DRIVER_DOCUMENTS_IMAGE_URL.$value;
    //     }
    // }
    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->whereNull('read_at');
    }
    public function center()
    {
        return $this->belongsTo(Center::class, 'center', 'center');
    }
}
