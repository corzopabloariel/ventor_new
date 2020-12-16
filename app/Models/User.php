<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'name',
        'docket',
        'email',
        'phone',
        'username',
        'password',
        'role',
        'discount',
        'start',
        'end'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* ================== */
    public static function type(String $role)
    {
        return self::where("role", $role);
    }

    public function hasRole($role)
    {
        return $this->role == strtoupper($role);
    }

    public function redirect()
    {
        $elements = [
            'EMP' => 'emp',
            'VND' => 'vnd',
            'USR' => 'client',
            'ADM' => 'adm'
        ];
        return $elements[$this->role];
    }

    /* ================== */
    public static function create($attr)
    {
        $model = new self;
        $model->name = $attr['name'];
        $model->username = $attr['username'];
        $model->docket = isset($attr['docket']) ? $attr['docket'] : NULL;
        $model->email = isset($attr['email']) ? strtolower($attr['email']) : NULL;
        $model->phone = isset($attr['phone']) ? $attr['phone'] : NULL;
        $model->password = \Hash::make($attr['password']);
        $model->role = $attr['role'];

        $model->save();

        return $model;
    }
}
