<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use App\Http\Resources\CartResource;

class Cart extends Model
{
    use SoftDeletes;

    protected $table = "cart";
    protected $fillable = [
        'uid',
        'data',
        'user_id'
    ];
    protected $casts = [
        'data'  => 'array'
    ];
    protected $appends = ['tmp', 'products'];
    public function getTmpAttribute() {

        if (Storage::disk('local')->exists('cart_'.$this->user_id.'_1.json')) {

            $tmpData = Storage::disk('local')->get('cart_'.$this->user_id.'_1.json');
            return json_decode($tmpData, true);

        }
        return array();

    }
    public function getQuantityAttribute() {

        return empty($this->data) ? count($this->tmp) : count($this->data);

    }
    public function getProductsAttribute() {

        return empty($this->data) ? $this->tmp : $this->data;

    }

    public static function one($request, $userId) {

        $user = User::find($userId);
        $cart = $user->lastCart;
        if ($cart) {

            $cartResource = new CartResource($cart);
            return
            array(
                'error'     => false,
                'status'    => 202,
                'message'   => 'OK',
                'elements'  => $cartResource
            );

        }
        return
        array(
            'error'     => true,
            'status'    => 404,
            'message'   => 'Carrito no válido'
        );

    }

    public function attributes($attr, $data = null) {

        if (empty($data)) {

            $model = (new self)->where('user_id', $attr['user_id'])->whereNull('uid')->first();
            if (!$model) {

                $model = $this;
                $model->user_id = $attr['user_id'];

            }

        } else {

            $model = $data;

        }
        if (!empty($attr['uid'])) {

            $model->uid = $attr['uid'];
            $model->data = isset($attr['data']) ? $attr['data'] : null;

        } else {

            $model->data = null;
            Storage::disk('local')->put('cart_'.$attr['user_id'].'_1.json', json_encode($attr['data'], JSON_UNESCAPED_UNICODE));

        }
        $model->save();

        return $model;

    }

    public static function createOrUpdate($request, $data = null) {

        $attributes = $request->all();

        if (!isset($attributes['test']) || isset($attributes['test']) && !$attributes['test']) {

            $cart = (new self)->attributes($attributes, $data);

        } else {

            $cart = self::first();

        }
        return self::one($request, $cart->user_id);

    }
    public static function products($request, int $userId, int $type) {

        $user = User::find($userId);
        $cart = $user->lastCart;
        if ($cart) {

            $elements = $type == 1 ? $cart->quantity : $cart->products;

        } else {

            $elements = $type == 1 ? 0 : array();

        }dd($elements);
        return
        array(
            'error'     => false,
            'status'    => 205,
            'message'   => 'OK',
            'element'   => $type == 2 ? CartProductResource::collection($elements) : $elements,
            'cartId'    => $cart->id ?? null
        );

    }
}
