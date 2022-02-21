<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Product;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        if ($request->has('simple')) {

            $data = collect(empty($this->data) ? $this->tmp : $this->data)->map(function($element) use ($request) {
                $product = Product::where('_id', $element['product'])->first();
                $part = $product->part;
                if ($part) {

                    $family = $part->family;
                    if ($family) {

                        $element['color'] = $family['color']['color'];

                    }

                }
                $element['product_id'] = $product->id;
                $element['code'] = $product->stmpdh_art;
                $element['quantity'] = (int) $element['quantity'];
                $element['name'] = $product->stmpdh_tex;
                $element['application'] = $this->application;
                $element['brands'] = $this->web_marcas;
                $total = round((float) $product->precio * $element['quantity'], 2);
                $element['price'] = array(
                    'unit'  => (float) $product->precio,
                    'total' => $total,
                    'time'  => time()
                );
                return $element;
            })->toArray();
            $price = collect($data)->sum(function ($product) {

                return $product['price']['total'];

            });

        } else {

            $data = collect(empty($this->data) ? $this->tmp : $this->data)->map(function($element) use ($request) {

                $product = Product::where('_id', $element['product'])->first();
                if ($product) {

                    $label = null;
                    $part = $product->part;
                    if ($part) {

                        $family = $part->family;
                        if ($family) {

                            $element['color'] = $family['color']['color'];
                            $label = '<small title="'.$family['name'].'" style="color: '.$family['color']['color'].'; border-color: '.$family['color']['color'].'">'.$family['name'].'</small>';
    
                        }

                    }
                    $element['product_id'] = $product->id;
                    $element['code'] = $product->stmpdh_art;
                    $element['quantity'] = (int) $element['quantity'];
                    $element['name'] = $product->stmpdh_tex;
                    $element['application'] = $this->application;
                    $element['brands'] = $this->web_marcas;
                    $total = round((float) $product->precio * $element['quantity'], 2);
                    $element['label'] = $label;
                    $element['input'] = array(
                        'step'   => $product->cantminvta,
                    );
                    $element['price'] = array(
                        'unit'  => array(
                            'float'     => (float) $product->precio,
                            'string'    => '$ '.number_format((float) $product->precio, 2, ',', '.')
                        ),
                        'total' => array(
                            'float'     => $total,
                            'string'    => '$ '.number_format($total, 2, ',', '.')
                        ),
                        'time'  => time()
                    );
                    return $element;

                }
                return null;

            })->filter(function($item) {

                return !empty($item);

            })->toArray();
            $price = collect($data)->sum(function ($product) {

                return $product['price']['total']['float'];

            });

        }

        if (!$request->has('simple')) {

            $element = array(
                'data'      => $data,
                'price'     => array(
                    'float'     => $price,
                    'string'    => '$ '.number_format($price, 2, ',', '.')
                ),
                'total'     => count($data)
            );

        } else {

            $element = array(
                'data'      => $data,
                'price'     => $price
            );

        }
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
