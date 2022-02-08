<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FamilyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array(
            "name" => $this->name,
            "name_slug" => $this->name_slug,
            "color" => $this->color,
        );
    }
}
