<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderCompleteResource extends JsonResource
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
            "id"        => $this->id,
            "is_test"   => $this->is_test,
            "title"     => $this->title,
            "obs"       => $this->obs,
            "user"      => new UserResource($this->user),
            "seller"    => new UserResource($this->seller),
            "client"    => new ClientResource($this->client),
            "transport" => new TransportResource($this->transport),
            "products"  => OrderProductResource::collection($this->orderProduct)
        );
    }
}
