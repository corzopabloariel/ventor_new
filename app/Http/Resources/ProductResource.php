<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PartResource;
use App\Models\User;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) {

        $images = array();
        $imageBase = "IMAGEN/{$this->codigo_ima[0]}/{$this->codigo_ima}";
        $imageBase = str_replace(' ', '%20', $imageBase);
        if (file_exists(configs("FOLDER").'/'.$imageBase.'.jpg')) {

            $type = pathinfo(configs("FOLDER").'/'.$imageBase.'.jpg', PATHINFO_EXTENSION);
            $images[] = array(
                'base64'    => 'data:image/'.$type.';base64,'.base64_encode(file_get_contents(configs("FOLDER").'/'.$imageBase.'.jpg')),
                'url'       => 'http://pedidos.ventor.com.ar/'.$imageBase.'.jpg'
            );

        }
        if ($request->has('complete')) {

            for ($i = 1; $i <= config('app.images'); $i++) {
                if (file_exists(configs("FOLDER").'/'.$imageBase.'-'.$i.'.jpg')) {

                    $type = pathinfo(configs("FOLDER").'/'.$imageBase.'-'.$i.'.jpg', PATHINFO_EXTENSION);
                    $images[] = array(
                        'base64'    => 'data:image/'.$type.';base64,'.base64_encode(file_get_contents(configs("FOLDER").'/'.$imageBase.'-'.$i.'.jpg')),
                        'url'       => 'http://pedidos.ventor.com.ar/'.$imageBase.'-'.$i.'.jpg'
                    );

                }
            }

        }
        if (empty($images)) {

            $images[] = array(
                'base64'    => null,
                'url'       => 'http://ventor.com.ar/images/no-img.png'
            );

        }

        if (!$request->has('simple')) {

            $element = array(
                'path'          => $this->_id,
                'code'          => $this->stmpdh_art,
                'use'           => $this->use,
                'name'          => $this->stmpdh_tex,
                'name_slug'     => $this->name_slug,
                'applications'  => ApplicationBasicResource::collection($this->applications),
                'application'   => $this->applications->map(function($item){ return $item->name; })->join(', '),
                'brands'        => BrandResource::collection($this->brands),
                'models'        => BrandResource::collection($this->models),
                'brand'         => $this->brands->map(function($item){ return '<li class="card__highlights__item">'.$item->name.'</li>'; })->join(''),
                'part'          => new PartResource($this->part),
                'subpart'       => new SubpartResource($this->subpart),
                'family'        => new FamilyResource($this->part->family),
                'modelo_anio'   => $this->modelo_anio,
                'cantminvta'    => $this->cantminvta,
                'dateIn'        => $this->fecha_ingr,
                'stock_mini'    => $this->stock_mini,
                'isSale'        => $this->liquidacion,
                'images'        => $images,
                'imagesString'  => collect($images)->map(function($img) {
                    return $img['base64'] ?? $img['url'];
                })->join('|')
            );

        } else {

            $element = array(
                'path'          => $this->_id,
                'code'          => $this->stmpdh_art,
                'use'           => $this->use,
                'name'          => $this->stmpdh_tex,
                'applications'  => ApplicationBasicResource::collection($this->applications),
                'application'   => $this->applications->map(function($item){ return $item->name; })->join(', '),
                'brands'        => BrandResource::collection($this->brands),
                'brand'         => $this->brands->map(function($item){ return '<li class="card__highlights__item">'.$item->name.'</li>'; })->join(''),
                'family'        => new FamilyResource($this->part->family),
                'modelo_anio'   => $this->modelo_anio,
                'cantminvta'    => $this->cantminvta,
                'image'         => $images[0]
            );

        }
        if ($request->has('price')) {

            if (
                $request->has('userId') && $request->get('userId') ||
                $request->has('pdf')
            ) {

                if (
                    $request->has('markup') &&
                    $request->get('markup') == 'venta'
                ) {

                    $user = User::find($request->get('userId'));
                    if ($user) {

                        $priceMarkup = empty($user->discount) ?
                            $this->precio :
                            round($this->precio * (1 + ($user->discount / 100)), 2);
                        $element['priceMarkup'] = '$ '.number_format($priceMarkup, 2, ",", ".");

                    }

                }
                $element['price'] = '$ '.number_format($this->precio, 2, ",", ".");

            }

        }
        ksort($element);
        return $element;
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
