<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Ventor\Ticket;

class Number extends Model
{
    use HasFactory;

    protected $fillable = [
        'order',
        'province',
        'name',
        'person',
        'internal',
        'email',
        'phone'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'email' => 'array',
        'phone' => 'array'
    ];

    public function printPhone()
    {
        $html = "";
        if (empty($this->phone))
            return $html;
        $html = collect($this->phone)->map(function($item) {
            $a = "";
            $type = ($item["tipo"] == "tel") ? "tel:" : "https://wa.me/";
            $a .= "<p class='text-truncate'>";
                $a .= $item["is_link"] ? "<a href='{$type}{$item["telefono"]}' target='blank'>" : "";
                    $a .= empty($item["visible"]) ? $item["telefono"] : $item["visible"];
                $a .= $item["is_link"] ? "</a>" : "";
            $a .= "</p>";
            return $a;
        })->join('');
        return $html;
    }

    public function printEmail()
    {
        $html = "";
        if (empty($this->email))
            return $html;
        $html = collect($this->email)->map(function($item) {
            $a = "";
            $a .= "<p class='text-truncate'>";
                $a .= "<a href='mailto:{$item["email"]}' target='blank'>";
                    $a .= $item["email"];
                $a .= "</a>";
            $a .= "</p>";
            return $a;
        })->join('');
        return $html;
    }

    public static function order(Request $request) {

        collect($request->ids)->map(function ($number_id, $key) {

            $number = self::find($number_id);
            Ticket::add(3, $number->id, 'numbers', 'Se modificó el valor', [$number->order, $key, 'order']);
            $number->fill(["order" => $key]);
            $number->save();

        });

        return responseReturn(false, 'Orden guardado');

    }

    public function getName() {
        return 'numbers';
    }
}
