<?php

namespace App\Models\Ventor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
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
            $date = date("d/m/Y H:i:s", strtotime($x->updated_at));
            $nameDate = (isset($x->user) ? $x->user->name.' - ' : 'ACTUALIZACIÓN AUTOMÁTICA - ').$date;
            return "<div class='p-2 border mt-3 text-wrap text-break'>{$x->obs}<p class='text-right text-muted'><small>{$nameDate}</small></p></div>";
        })->join('');
        return empty($value) ? "<div class='p-2 border mt-3 text-center text-wrap'>Sin información</div>" : $value;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    /**
     * 
     */
    public static function add(Int $type, Int $id, String $table, String $obs, $compare = [], Bool $addObs = true, Bool $addTicketToUser = false) {
        $attr = $compare[2];
        $valueOld = $compare[0];
        $valueNew = $compare[1];
        if (!empty($attr))
            $obs .= " de \"{$attr}\"";
        if (!empty($valueOld) && gettype($valueOld) == "array")
            $valueOld = json_encode($valueOld);
        if (!empty($valueNew) && gettype($valueNew) == "array")
            $valueNew = json_encode($valueNew);
        if ($addObs && !empty($valueOld) && !empty($valueNew))
            $obs .= " de [{$valueOld}] a [$valueNew]";
        if ($addObs) {
            self::create([
                "type" => $type,
                "table" => $table,
                "table_id" => $id,
                'obs' => $obs,
                'user_id' => $addTicketToUser ? $id : \Auth::user()->id
            ]);
        }
    }
}
