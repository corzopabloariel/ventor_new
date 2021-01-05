<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PartResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            '_id' => $this->_id,
            'stmpdh_art' => $this->stmpdh_art,
            'use' => $this->use,
            'name' => $this->stmpdh_tex,
            'name_slug' => $this->name_slug,
            'price' => $this->precio,
            'brand' => $this->web_marcas,
            'brand_slug' => $this->marca_slug,
            'part' => new PartResource($this->part),
            'subpart' => new SubpartResource($this->subpart),
            'family' => new FamilyResource($this->part->family),
            'modelo_anio' => $this->modelo_anio,
            'cantminvta' => $this->cantminvta,
            'dateIn' => $this->fecha_ingr,
            'stock_mini' => $this->stock_mini,
            'isSale' => $this->liquidacion != "N",
            'images' => "/IMAGEN/{$this->codigo_ima[0]}/{$this->codigo_ima}"
        ];
    }
}
