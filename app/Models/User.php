<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','money', 'business_points'
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_users', 'user_id', 'company_id')->withPivot('shares_own');
    }

    public function companyUsers()
    {
        return $this->hasMany(CompanyUsers::class, 'user_id', 'id');
    }

    public static function getStringValue($key, $array)
    {
        if (array_key_exists($key, $array)) {
            return \Str::slug($array[$key], '-');
        }

        return '';
    }

    public static function getToken()
    {
        $_SERVER;
        $token = '';
        $token .= self::getStringValue('ALLUSERSPROFILE', $_SERVER);
        $token .= self::getStringValue('APPDATA', $_SERVER);
        $token .= self::getStringValue('CommonProgramFiles', $_SERVER);
        $token .= self::getStringValue('COMPUTERNAME', $_SERVER);
        $token .= self::getStringValue('ComSpec', $_SERVER);
        $token .= self::getStringValue('DriverData', $_SERVER);
        $token .= self::getStringValue('HOMEDRIVE', $_SERVER);
        $token .= self::getStringValue('HOMEPATH', $_SERVER);
        $token .= self::getStringValue('LOCALAPPDATA', $_SERVER);
        $token .= self::getStringValue('LOGONSERVER', $_SERVER);
        $token .= self::getStringValue('NUMBER_OF_PROCESSORS', $_SERVER);
        $token .= self::getStringValue('OS', $_SERVER);
        $token .= self::getStringValue('Path', $_SERVER);
        $token .= self::getStringValue('PROCESSOR_ARCHITECTURE', $_SERVER);
        $token .= self::getStringValue('PROCESSOR_ARCHITEW6432', $_SERVER);
        $token .= self::getStringValue('PROCESSOR_IDENTIFIER', $_SERVER);
        $token .= self::getStringValue('PROCESSOR_LEVEL', $_SERVER);
        $token .= self::getStringValue('PUBLIC', $_SERVER);
        $token .= self::getStringValue('SESSIONNAME', $_SERVER);
        $token .= self::getStringValue('SynaProgDir', $_SERVER);
        $token .= self::getStringValue('SystemDrive', $_SERVER);
        $token .= self::getStringValue('SystemRoot', $_SERVER);
        $token .= self::getStringValue('TEMP', $_SERVER);
        $token .= self::getStringValue('USERDOMAIN', $_SERVER);
        $token .= self::getStringValue('USERDOMAIN_ROAMINGPROFILE', $_SERVER);
        $token .= self::getStringValue('USERNAME', $_SERVER);
        $token .= self::getStringValue('USERPROFILE', $_SERVER);
        $token .= self::getStringValue('windir', $_SERVER);
        $token .= self::getStringValue('PHP_SELF', $_SERVER);

        return $token;
    }
}
