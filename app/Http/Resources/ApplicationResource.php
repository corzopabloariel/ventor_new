<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return array(
            'title'     => $this->application->title,
            'image'     => $this->application->image,
            'type'      => $this->type,
            'product'   => new ProductResource($this->product)
        );
    }
}
