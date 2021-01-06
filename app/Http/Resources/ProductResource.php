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
        $precio = $this->precio;
        $precio += session()->get('markup') * $precio;
        $name = "/IMAGEN/{$this->codigo_ima[0]}/{$this->codigo_ima}";
        $images = ["{$name}.jpg"];
        for ($i = 1; $i <= 10; $i++) {
            if (file_exists(public_path() . "{$name}-{$i}.jpg"))
                $images[] = "{$name}-{$i}.jpg";
        }
        return [
            '_id' => $this->_id,
            'search' => $this->search,
            'code' => $this->stmpdh_art,
            'use' => $this->use,
            'name' => $this->stmpdh_tex,
            'name_slug' => $this->name_slug,
            'price' => "$ " . number_format($precio, 2, ".", "."),
            'priceNumber' => $precio,
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
            'images' => $images,
        ];
    }

    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function withResponse($request, $response)
    {
        $response->header('X-Value', 'True');
    }
}
