<?php

namespace App\Models\Ventor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'table',
        'table_id',
        'obs',
        'user_id'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    /* ================== */
    public static function show(String $id, String $table)
    {
        $data = self::where("table", $table)->where("table_id", $id)->orderBy("id", "DESC")->get();
        $value = collect($data)->map(function($x) {
            $date = date("d/m/Y H:i:s", strtotime($x->created_at));
            return "<div class='p-2 border mt-3 text-wrap text-break'>{$x->obs}<p class='text-right text-muted'><small>{$x->user->name} - {$date}</small></p></div>";
        })->toArray();
        $value = implode("", $value);
        return empty($value) ? "<div class='p-2 border mt-3 text-center text-wrap'>Sin información</div>" : $value;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
}
