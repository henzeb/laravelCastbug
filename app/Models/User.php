<?php

namespace App\Models;

use UnitEnum;
use App\MyUnitEnum;
use App\BasicEnumCast;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status'=> MyUnitEnum::class,
    ];

    protected function getEnumCastableAttributeValue($key, $value)
    {
        if (is_null($value)) {
            return null;
        }
        dump('getting cast value');
        $castType = $this->getCasts()[$key];

        if ($value instanceof $castType) {
            return $value;
        }

        return $this->make($castType, $value);
    }

    protected function setEnumCastableAttribute($key, $value)
    {
        dump('setting cast value');
        $enumClass = $this->getCasts()[$key];

        if (! isset($value)) {
            $this->attributes[$key] = null;
        } elseif ($value instanceof $enumClass) {
            $this->attributes[$key] = $value->name;
        } else {
            $this->attributes[$key] = $this->make($enumClass, $value);
        }
    }

    private function make(string $enumClass, mixed $value): UnitEnum
    {

        /**
         * @var $enumClass UnitEnum
         */

        foreach($enumClass::cases() as $case) {
            if($case->name===$value) {
                return $case;
            }
        }
        throw new \Exception('Value does not exist');
    }
}
