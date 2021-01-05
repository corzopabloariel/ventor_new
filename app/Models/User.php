<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Models\Ventor\Ticket;
use App\Models\Client;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'name',
        'docket',
        'dockets',
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

    protected $casts = [
        'dockets' => 'array'
    ];

    /* ================== */
    public static function type(String $role)
    {
        return self::where("role", $role);
    }

    public function history($data)
    {
        foreach(['uid','name','docket','email','phone','username','role','discount','start','end'] AS $attr)
        {
            if (!isset($data[$attr]))
                continue;
            $valueNew = $data[$attr];
            $valueOld = $this[$attr];
            if ($valueOld != $valueNew) {
                Ticket::create([
                    'type' => 3,
                    'table' => 'users',
                    'table_id' => $this->id,
                    'obs' => '<p>Se modificó el valor de "' . $attr . '" de [' . htmlspecialchars($valueOld) . '] <strong>por</strong> [' . htmlspecialchars($valueNew) . ']</p>',
                    'user_id' => \Auth::user()->id
                ]);
            }
        }
    }

    public function hasRole($role)
    {
        return $this->role == "ADM" || $this->role == "EMP" || $this->role == "VND" || $this->role == strtoupper($role);
    }

    public function isAdmin()
    {
        return $this->role == "ADM";
    }

    public function isShowQuantity()
    {
        return $this->role != "USR";
    }

    public function isShowData()
    {
        return $this->role == "USR";
    }

    public function redirect()
    {
        $elements = [
            'EMP' => '/',
            'VND' => '/',
            'USR' => '/',
            'ADM' => 'adm'
        ];
        return $elements[$this->role];
    }

    public function getClient()
    {
        if (empty($this->uid))
            return null;
        return Client::one($this->uid);
    }

    /* ================== */
    public static function removeAll($arr, $in, $role = "USER") {
        // 0 es usuario de prueba
        if ($in)
            $users = self::type($role)->where("username", "!=", "0")->whereIn("id", $arr)->get();
        else
            $users = self::type($role)->where("username", "!=", "0")->whereNotIn("id", $arr)->get();
        if ($users)
        {
            foreach($users AS $user) {
                $data = "";
                $data .= "<li><strong>Nombre:</strong> {$user->name}</li>";
                $data .= "<li><strong>Legajo:</strong> {$user->docket}</li>";
                $data .= "<li><strong>Usuario:</strong> {$user->username}</li>";
                $data .= "<li><strong>Email:</strong> {$user->email}</li>";
                $data .= "<li><strong>Role:</strong> {$user->role}</li>";
                Ticket::create([
                    'type' => 2,
                    'table' => 'users',
                    'table_id' => $user->id,
                    'obs' => '<p>Se eliminó el registro</p><ul>' . $data . '</ul>',
                    'user_id' => \Auth::user()->id
                ]);
            }
        }
    }
    public static function create($attr)
    {
        $model = new self;
        $model->uid = isset($attr['uid']) ? $attr['uid'] : NULL;
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

    public static function mod($attr, $model)
    {
        $model->uid = isset($attr['uid']) ? $attr['uid'] : NULL;
        $model->name = $attr['name'];
        $model->username = $attr['username'];
        $model->docket = isset($attr['docket']) ? $attr['docket'] : NULL;
        $model->email = isset($attr['email']) ? strtolower($attr['email']) : NULL;
        $model->phone = isset($attr['phone']) ? $attr['phone'] : NULL;
        $model->password = $attr['password'];
        $model->role = $attr['role'];

        $model->save();
        return $model;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPassword($token));
    }
}
