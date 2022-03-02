<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CartResource;
use App\Http\Resources\ClientResource;
use App\Http\Resources\OrderCompleteResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\TransportResource;
use App\Exports\OrderExport;
use Excel;
class Order extends Model
{
    protected $fillable = [
        'user_id',
        'client_id',
        'transport_id',
        'title',
        'obs',
        'email',
        'sent',
        'is_test'
    ];

    protected $casts = [
        'email'     => 'array',
        'is_test'   => 'boolean',
        'sent'      => 'boolean'
    ];
    public static function data($request, $paginate, $client = null)
    {
        if (empty($client)) {
            $data = self::where("user_id", \Auth::user()->id);
        } else {
            $data = self::where("client_id", $client->id);
        }
        $data = $data->orderBy("id", "DESC")
                ->paginate((int) $paginate);
        return $data;
    }
    public function user() {

        return $this->belongsTo(User::class, 'user_id', 'id');

    }
    public function seller() {

        return $this->belongsTo(User::class, 'seller_id', 'id');

    }
    public function transport() {

        return $this->belongsTo(Transport::class, 'transport_id', 'id');

    }
    public function client() {

        return $this->belongsTo(Client::class, 'client_id', 'id');

    }
    public function orderProduct() {

        return $this->hasMany(OrderProduct::class, 'order_id', 'id');

    }
    public function products() {

        return $this->belongsToMany(Product::class,'orders_products', 'order_id', 'product_id');

    }
    public function export($name = 'PEDIDO.xls') {

        set_time_limit(600);
        $order = $this;
        $fileXLS = 'pedido-'.$this->id.':'.$this->client_id.':'.$this->user_id.'.xls';
        $rows = $this->orderProduct->map(function($product) use ($order) {
            return [
                'exp_1' => 'MN',
                'exp_2' => '',
                'cod' => $product->product['stmpdh_art'],
                'exp_4' => '',
                'cnt' => $product->quantity,
                'precio' => $product->price,
                'bonif1' => '',
                'bonif2' => '',
                'observ' => '',
                'cliente' => $order->is_test ? 'PRUEBA' : $order->client['nrocta'],
                'destrp' => $order->transport['description'] ?? '',
                'dirtrp' => $order->transport['address'] ?? '',
                'idpedido' => $order->id
            ];
        })->toArray();
        Excel::store(new OrderExport($rows), $fileXLS, 'local');
        return array(
            'file'      => storage_path('app').'/'.$fileXLS,
            'name'      => $name,
            'mime'      => 'application/vnd.ms-excel',
            'delete'    => true
        );

    }
    /* ================== */
    public static function gets($request) {

        $orders = self::where('id', '!=', '');
        $response = array(
            'error'     => false,
            'status'    => 202,
            'message'   => 'OK',
            'total'     => array(
                'pages'     => 0,
                'orders'    => 0
            ),
            'page'      => 0,
            'orders'    => NULL
        );
        if ($request->has('search') && !empty($request->search)) {}
        $paginate = $request->has('paginate') ? (int) $request->get('paginate') : 10;
        $page = $request->has('page') ? (int) $request->get('page') : 1;
        $total = $orders->count();
        $totalPages = ceil($total / $paginate);
        $response['total']['pages'] = $totalPages;
        $response['total']['orders'] = $total;
        $response['page'] = $page;
        $response['orders'] = OrderCompleteResource::collection(
            $orders->
                orderBy('id', 'DESC')->
                paginate($paginate)
        );
        return response(
            $response,
            202
        );

    }
    public static function element($request) {

        $user = User::find($request->user_id);
        $client = $user->client;
        if ($user) {

            $cart = $user->lastCart;
            $cartResource = new CartResource($cart);
            $data = json_decode($request->data, true);
            $data['user'] = $user;
            $data['cart'] = json_decode($cartResource->toJson(), true);
            if (!empty($data['client'])) {

                $userClient = User::find($data['client']);
                $data['userClient'] = $userClient;
                $data['client'] = $userClient->client;
                unset($data['client']);

            } else {

                if ($client) {

                    $data['userClient'] = $user;
                    $data['client'] = $client->id;

                }

            }
            if (!empty($data['transport'])) {

                $transport = Transport::where('code', (string) $data['transport'])->first();
                unset($data['transport']);
                if ($transport) {

                    $data['transport_id'] = $transport->id;

                }

            } else {

                if ($client) {

                    $data['transport_id'] = $client->transport_id;

                }

            }
            list($order, $cart) = self::create($data, $cart);
            return
            array(
                'error'     => false,
                'status'    => 202,
                'message'   => 'OK',
                'elements'  => array(
                    'order' => new OrderResource($order),
                    'cart'  => $cart
                )
            );

        }
        return
        array(
            'error' => true,
            'status' => 401,
            'message' => 'Usuario no encontrado'
        );

    }
    public static function create($attribute, $cart) {

        $docketClient = $codeSeller = null;
        $date = date("Ymd-His");
        $model = new self;
        $model->user_id = $attribute['user']->id;
        $model->obs = isset($attribute['observations']) && !empty($attribute['observations']) ? $attribute['observations'] : null;
        $model->is_test = ($attribute['user']->username == 'pc' || $attribute['user']->test) ? true : false;
        if (!empty($attribute['userClient'])) {

            $client = $attribute['userClient']->client;
            $model->client_id = $client->id;
            $model->seller_id = $client->seller->id ?? NULL;
            $docketClient = $client->nrocta;
            $codeSeller = $client->seller->docket;

        } else {

            $client = $attribute['user']->client;
            $docketClient = $client->nrocta;
            $codeSeller = $client->seller->docket;

        }
        if (isset($attribute['transport_id'])) {

            $model->transport_id = $attribute['transport_id'];

        }
        $model->save();
        $model->title = "Pedido {$codeSeller}-{$docketClient}-{$model->id}-{$date} Cliente {$docketClient}";
        $model->save();
        $cart->fill(
            array(
                'uid'   => $model->id,
                'data'  => $attribute['cart']
            )
        );
        $cart->save();
        foreach($attribute['cart']['data'] AS $product) {

            $orderProduct = new OrderProduct(
                array(
                    'order_id'      => $model->id,
                    'product_id'    => $product['product_id'],
                    'price'         => isset($product['price']['unit']['float']) ? $product['price']['unit']['float'] : $product['price']['unit'],
                    'quantity'      => $product['quantity']
                )
            );
            $orderProduct->save();

        }
        Storage::delete('cart_'.$model->user_id.'_1.json');
        return array(
            $model,
            $cart
        );
    }
    public static function one($request, String $code) {

        $order = self::find($code);
        if ($order) {

            return
            array(
                'error'     => false,
                'status'    => 202,
                'message'   => 'OK',
                'order'     => new OrderCompleteResource($order)
            );

        }
        return
        array(
            'error'     => true,
            'status'    => 404,
            'message'   => 'Orden no encontrada'
        );

    }

}
