<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ApplicattionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function($app) {

            $data = collect($app->element)->mapWithKeys(function($elem, $key) {

                $product = Product::find($elem['code']);
                $imageBase = "IMAGEN/{$product->codigo_ima[0]}/{$product->codigo_ima}";
                $imageBase = str_replace(' ', '%20', $imageBase);
                return array(
                    $key    => new ProductResource($product),
                    'image' => 'https://ventor.com.ar/'.$imageBase.'.jpg'
                );

            });
            $data['title'] = $app->title;
            return $data;

        });
    }
}
