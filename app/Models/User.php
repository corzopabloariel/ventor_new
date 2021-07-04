<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Http\Request;

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
        'end',
        'limit',
        'test'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'permissions',
        'routes',
        'actions'
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
        'dockets' => 'array',
        'test' => 'bool'
    ];

    public function getName() {
        return 'users';
    }
    public function getConfigAttribute()
    {
        return DB::table('config_user')->where('user_id', $this->id)->first();
    }
    public function setConfig($attr)
    {
        $config = self::getConfigAttribute();
        $attr["user_id"] = $this->id;
        if (empty($config)) {
            $attr['active_url'] = false;
            $attr['url'] = strtolower($this->username);
            $attr['active_favorite'] = false;
            $attr['paginate'] = configs("PAGINADO");
            $attr['created_at'] = date("Y-m-d H:i:s");
            $attr['updated_at'] = date("Y-m-d H:i:s");
            DB::table('config_user')->insert($attr);
        } else {
            $attr['updated_at'] = date("Y-m-d H:i:s");
            $affected = DB::table('config_user')
                ->where('id', $config->id)
                ->update($attr);
        }
    }

    public function downloads()
    {
        return $this->hasMany('App\Models\Ventor\DownloadUser','user_id','id');
    }

    public function tickets()
    {
        return $this->hasMany('App\Models\Ventor\Ticket','user_id','id');
    }

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
                    'user_id' => \Auth::check() ? \Auth::user()->id : null
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
        return $this->role == "USR" && !empty($this->uid);
    }

    public function redirect()
    {
        $elements = [
            'EMP' => 'adm',
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
        $client = Client::one($this->uid);
        if (empty($client)) {
            $client = Client::one($this->docket, 'nrocta');
            if (!empty($client)) {
                Ticket::create([
                    'type' => 3,
                    'table' => 'users',
                    'table_id' => $this->id,
                    'obs' => '<p>Se modificó el valor de "uid" de [' . $this->uid . '] <strong>por</strong> [' . $client->_id . ']</p>',
                    'user_id' => \Auth::check() ? \Auth::user()->id : null
                ]);
                $this->fill(['uid' => $client->_id]);
                $this->save();
            }
        }
        return $client;
    }

    /* ================== */
    public static function removeAll($arr, $in, $role = "USER") {
        // 0 es usuario de prueba
        if ($in)
            $users = self::type($role)->where("test", false)->where("username", "!=", "0")->whereIn("id", $arr)->get();
        else
            $users = self::type($role)->where("test", false)->where("username", "!=", "0")->whereNotIn("id", $arr)->get();
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
                    'user_id' => \Auth::check() ? \Auth::user()->id : null
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
        $model->limit = isset($attr['limit']) ? $attr['limit'] : 0;
        $model->test = isset($attr['test']) ? $attr['test'] : false;

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
        $model->limit = isset($attr['limit']) ? $attr['limit'] : 0;
        $model->test = isset($attr['test']) ? $attr['test'] : false;

        $model->save();
        return $model;
    }

    // Clientes
    public function scopeUsr($q) {

        return $q->where("role", "USR")->where("test", false);

    }

    // Empleados
    public function scopeEmp($q) {

        return $q->whereIn("role", ["ADM","EMP"])->where("username", "!=", "pc");

    }

    // Vendedores
    public function scopeSell($q) {

        return $q->where("role", "VND")->where("test", false);

    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPassword($token));
    }

    public function access() {
        $access = [];
        $userAccess = UserAccess::where('docket', $this->docket)->get();
        if ($userAccess) {
            $access = collect($userAccess)->mapWithKeys(function($item) {
                return [$item['route'] => [
                    'create' => $item['create'],
                    'read' => $item['read'],
                    'update' => $item['update'],
                    'delete' => $item['delete']
                ]];
            })->toArray();
        }
        return $access;
    }

    public function getActionsAttribute() {
        return [
            'create' => 'Crear',
            'read' => 'Leer',
            'update' => 'Actualizar',
            'delete' => 'Eliminar'
        ];
    }

    public function getRoutesAttribute() {
        return [
            'slider' => 'Sliders',
            'content' => 'Contenidos',
            'news' => 'Novedades',
            'downloads' => 'Descargas',
            'orders' => 'Pedidos',
            'emails' => 'Emails',
            'clients' => 'Clientes',
            'data' => 'Datos de Ventor',
            'users' => 'Usuarios',
            'texts' => 'Textos',
            'configs' => 'Configuración',
            'sellers' => 'Vendedores',
            'employees' => 'Empleados',
            'transports' => 'Transportes',
            'products' => 'Productos',
            'numbers' => 'Números'
        ];
    }

    public function getPermissionsAttribute() {
        return $this->access();
    }

    public function updatePermissions(Request $request) {
        \DB::beginTransaction();
        try {
            // Borro todos los permisos
            UserAccess::where('docket', $this->docket)->delete();
            
            $hidden = $request->hidden;
            $data = $request->except(['_token', 'hidden']);
            $docket = $this->docket;
            $permissions = collect($hidden)->mapWithKeys(function($item, $route) use ($data, $docket) {
                $permissions[$route] = [];
                if (isset($data[$route])) {
                    $permissionsUser = $data[$route];
                    $permissions[$route] = collect($item)->mapWithKeys(function($elements, $key) use ($permissionsUser) {
                        return [$key => isset($permissionsUser[$key])];
                    });
                    $create = array_merge([
                        'docket' => $docket,
                        'route' => $route,
                    ], $permissions[$route]->toArray());
                    UserAccess::create($create);
                }
                return $permissions;
            })->filter(function($item) {
                return !empty($item);
            })->toArray();
            DB::commit();
            return responseReturn(false, 'Accesos y permisos actualizados', 0, 200, ['permissions' => $permissions]);
        } catch (\Throwable $th) {
            DB::rollback();
            return responseReturn(false, 'Ocurrió un error en el servidor', 1);
        }
    }

    public static function updateCollection(Bool $fromCron = false) {

        set_time_limit(0);
        $model = new self;
        $properties = $model->getFillable();
        $errors = [];
        $users = [];
        $source = implode('/', [public_path(), config('app.files.folder'), configs("FILE_EMPLOYEES", config('app.files.employees'))]);
        if (file_exists($source)) {

            $file = fopen($source, 'r');
            while (!feof($file)) {

                $row = trim(fgets($file));
                $row = utf8_encode($row);
                if (empty($row) || strpos($row, 'Cuenta') !== false) continue;
                $elements = array_map(
                    'clearRow',
                    explode(configs('SEPARADOR'), $row)
                );
                if (empty($elements)) continue;
                try {

                    $data = array_combine(['docket', 'name', 'username', 'email'], $elements);
                    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                        $data['phone'] = $data['email'];
                        unset($data['email']);
                    }
                    $user = self::where("username", "EMP_{$data['username']}")->first();
                    $data['password'] = config('app.pass');
                    $data['username'] = "EMP_{$data['username']}";
                    $data['role'] = 'EMP';
                    if ($data['username'] == 'EMP_28465591' || $data['username'] == 'EMP_12557187' || $data['username'] == 'EMP_12661482')
                        $data['role'] = 'ADM';
                    if ($user) {
                        $user->history($data);
                        $data['password'] = \Hash::make(config('app.pass'));
                        $user->fill($data);
                        $user->save();
                    } else
                        $user = self::create($data);
                    $users[] = $user->id;

                } catch (\Throwable $th) {

                    $errors[] = $elements;

                }

            }
            if (!empty($users)) {
                self::removeAll($users, 0, "ADM");
                self::removeAll($users, 0, "EMP");
                self::emp()->whereNotIn("id", $users)->delete();
            }
            fclose($file);

            if ($fromCron) {

                return responseReturn(true, 'Empleados insertados: '.self::emp()->count().' / Errores: '.count($errors));

            }

            return responseReturn(false, 'Empleados insertados: '.self::emp()->count().' / Errores: '.count($errors));

        }

        if ($fromCron) {

            return responseReturn(true, $source, 1, 400);

        }

        return responseReturn(true, 'Archivo no encontrado', 1, 400);

    }


    public static function updateSellerCollection(Bool $fromCron = false) {

        set_time_limit(0);
        $model = new self;
        $properties = $model->getFillable();
        $errors = [];
        $users = [];
        $source = implode('/', [public_path(), config('app.files.folder'), configs("FILE_SELLERS", config('app.files.sellers'))]);
        if (file_exists($source)) {

            $file = fopen($source, 'r');
            while (!feof($file)) {

                $row = trim(fgets($file));
                $row = utf8_encode($row);
                if (empty($row) || strpos($row, 'Apellido,') !== false) continue;
                $elements = array_map(
                    'clearRow',
                    explode(configs('SEPARADOR'), $row)
                );
                if (empty($elements)) continue;
                try {

                    $data = array_combine(['docket', 'name', 'username', 'phone', 'email'], $elements);
                    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                        $data['phone'] = $data['email'];
                        unset($data['email']);
                    }
                    $user = self::where("username", "VND_{$data['username']}")->first();
                    $data['password'] = config('app.pass');
                    $data['username'] = "VND_{$data['username']}";
                    $data['role'] = 'VND';
                    if ($user) {
                        if (empty($user->dockets))
                            $data["dockets"] = [];
                        else
                            $data["dockets"] = $user->dockets;
                        if (!in_array($data["docket"], $data["dockets"]))
                            $data["dockets"][] = $data['docket'];
                        $data["docket"] = $data["dockets"][0];
                        $user->history($data);
                        $data['password'] = \Hash::make(config('app.pass'));
                        $user->fill($data);
                        $user->save();
                    } else
                        $user = User::create($data);
                    $users[] = $user->id;

                } catch (\Throwable $th) {

                    $errors[] = $elements;

                }

            }
            if (!empty($users)) {
                self::removeAll($users, 0, "VND");
                self::sell()->whereNotIn("id", $users)->delete();
            }
            fclose($file);

            if ($fromCron) {

                return responseReturn(true, 'Vendedores insertados: '.self::sell()->count().' / Errores: '.count($errors));

            }

            return responseReturn(false, 'Vendedores insertados: '.self::sell()->count().' / Errores: '.count($errors));

        }

        if ($fromCron) {

            return responseReturn(true, $source, 1, 400);

        }

        return responseReturn(true, 'Archivo no encontrado', 1, 400);

    }
}
