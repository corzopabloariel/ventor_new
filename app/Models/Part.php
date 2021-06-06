<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Ventor\Ticket;

class Part extends Model
{
    protected $table = "parts";
    protected $fillable = [
        'name',
        'family_id'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function subparts()
    {
        return $this->hasMany('App\Models\Subpart','part_id','id')->get();
    }

    public function family()
    {
        return $this->belongsTo('App\Models\Family','family_id','id');
    }

    public static function order(Request $request) {

        collect($request->part)->map(function ($part_id, $key) use ($request) {

            $part = self::find($part_id);
            $valueOld = $part->family_id;
            $valueNew = empty($request->family[$key]) ? null : $request->family[$key];
            $part->fill(["family_id" => $valueNew]);
            $part->save();
            if ($valueOld != $valueNew) {

                Ticket::add(3, $part->id, 'parts', 'Se modificó el valor', [$valueOld, $valueNew, 'family_id']);
                collect($part->subparts())->each(function ($subpart, $key) use ($valueOld, $valueNew) {

                    $subpart->fill(["family_id" => $valueNew]);
                    $subpart->save();
                    Ticket::add(3, $subpart->id, 'subparts', 'Se modificó el valor', [$valueOld, $valueNew, 'family_id']);

                });

            }

        });

        return responseReturn(false, 'Categorías modificadas');

    }
}
