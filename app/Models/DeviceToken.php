<?php

namespace App\Modelss;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int user_id
 * @property string token
 */
class DeviceToken extends Model
{
    /******* Properties *******/
    protected $fillable = [
        'token',
        'user_id',
    ];

    /**
     * Remove device token.
     *
     * @param string $token
     */
    public static function remove(string $token)
    {
        $deviceToken = self::where(['token' => $token, 'user_id' => User::getJWTUser()->id])->first();
        if ($deviceToken) {
            $deviceToken->forceDelete();
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function setKeysForSaveQuery(Builder $query)
    {
        return $query
            ->where('token', $this->getAttribute('token'))
            ->where('user_id', $this->getAttribute('user_id'));
    }
}
