<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'value',
        'visible',
        'description'
    ];

    protected $casts = [
        'visible' => 'boolean'
    ];

    public function getName() {
        return 'configs';
    }
    /* ================== */
    public static function create($attr, Bool $edit = false)
    {
        $config = self::where("name", $attr['name'])->first();
        if ($config && !$edit)
            return false;
        if (!$config)
            $config = new self;
        $config->name = $attr['name'];
        $config->value = $attr['value'];
        $config->visible = isset($attr['visible']) ? $attr['visible'] : true;

        $config->save();
        return $config;
    }

    public function gets() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'value' => $this->value,
            'visible' => $this->visible,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
