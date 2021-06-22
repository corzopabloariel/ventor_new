<?php

namespace App\Models\Ventor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Ventor extends Model
{
    use HasFactory;
    public $timestamps = false;
    private $links = [
        ["link" => "/", "name" => "Inicio"],
        ["link" => "empresa", "name" => "Empresa"],
        ["link" => "descargas", "name" => "Descargas"],
        ["link" => "productos", "name" => "Productos", "login" => ["pedido", "Pedido"]],
        ["sub" => "atencion", "links" => [
                ["link" => "transmision", "name" => "Análisis de transmisión"],
                ["link" => "pagos", "name" => "Información sobre pagos"],
                ["link" => "consulta", "name" => "Consulta general"]
            ]
        ],
        ["link" => "calidad", "name" => "Calidad"],
        //["link" => "trabaje", "name" => "Trabaje con nosotros"],
        ["link" => "contacto", "name" => "Contacto"]
    ];
    protected $table = 'ventor';
    protected $fillable = [
        'address',
        'captcha',
        'phone',
        'email',
        'social',
        'metadata',
        'images',
        'section',
        'miscellaneous',
        'forms'
    ];
    protected $dates = [];

    protected $casts = [
        'address' => 'array',
        'captcha' => 'array',
        'phone' => 'array',
        'email' => 'array',
        'social' => 'array',
        'metadata' => 'array',
        'images' => 'array',
        'section' => 'array',
        'miscellaneous' => 'array',
        'forms' => 'array'
    ];

    public function getName() {
        return 'data';
    }

    public function formPrint()
    {
        if (empty($this->forms))
            return null;
        $grouped = collect($this->forms)->mapToGroups(function ($item, $key) {
            return [$item['form'] => $item['email']];
        })->toArray();
        return $grouped;
    }

    public function socialPrint()
    {
        $social = [
            'linkedin' => '<i class="fab fa-linkedin-in"></i>',
            'youtube' => '<i class="fab fa-youtube"></i>',
            'twitter' => '<i class="fab fa-twitter"></i>',
            'facebook' => '<i class="fab fa-facebook-f"></i>',
            'instagram' => '<i class="fab fa-instagram"></i>'
        ];
        $html = "";
        if (empty($this->social))
            return $html;
        $html = collect($this->social)->map(function($item) use ($social) {
            $a = "";
            $a .= "<a target='blank' href='{$item["url"]}' target='blank'>";
                $a .= $social[$item["redes"]] . " {$item["titulo"]}";
            $a .= "</a>";
            return $a;
        })->join('');
        return "<div class='social-network'>{$html}</div>";
    }

    public function addressPrint()
    {
        $html = "";
        if (empty($this->address))
            return $html;
        $html .= "<p>";
            $html .= "<a href='{$this->address["link"]}' target='blank'>";
                $html .= "{$this->address["calle"]} {$this->address["altura"]} ({$this->address["cp"]})<br/>";
                $html .= "{$this->address["provincia"]}, {$this->address["localidad"]}";
            $html .= "</a>";
        $html .= "</p>";
        $html = "<i class='fas fa-map-marked-alt'></i><div class='data'>{$html}</div>";
        return $html;
    }

    public function phonesPrint()
    {
        $html = "";
        if (empty($this->phone))
            return $html;
        $html = collect($this->phone)->map(function($item) {
            $a = "";
            $type = ($item["tipo"] == "tel") ? "tel:" : "https://wa.me/";
            $a .= "<p>";
                $a .= $item["is_link"] ? "<a href='{$type}{$item["telefono"]}' target='blank'>" : "";
                    $a .= empty($item["visible"]) ? $item["telefono"] : $item["visible"];
                $a .= $item["is_link"] ? "</a>" : "";
            $a .= "</p>";
            return $a;
        })->join('');
        $html = "<i class='fas fa-phone-alt'></i><div class='data'>{$html}</div>";
        return $html;
    }

    public function emailsPrint()
    {
        $html = "";
        if (empty($this->email))
            return $html;
        $html = collect($this->email)->map(function($item) {
            $a = "";
            $a .= "<p>";
                $a .= "<a href='mailto:{$item["email"]}' target='blank'>";
                    $a .= $item["email"];
                $a .= "</a>";
            $a .= "</p>";
            return $a;
        })->join('');
        $html = "<i class='fas fa-envelope mt-1'></i><div class='data'>{$html}</div>";
        return $html;
    }

    public function sitemap(String $type, $page = null, $classLI = "")
    {
        $html = "";
        $html = collect($this->links)->map(function($item) use ($type, $page, $classLI) {
            $a = "";
            if ($type == "header" && isset($item["link"]) && $item["link"] == "/")
                return $a;
            if (isset($item["sub"])) {
                $pre = $item["sub"];
                if ($type == "footer" || $type == "mobile") {
                    $a = collect($item["links"])->map(function($item) use ($pre, $classLI, $page) {
                        $a = "";
                        $url = \url::to("{$pre}/{$item["link"]}");
                        $name = $item["name"];
                        $class = "";
                        if (!empty($page) && isset($item["link"]) && $item["link"] == $page)
                            $class = "active";
                        $a .= "<li class='$classLI'>";
                            $a .= "<a class='$class' href='{$url}'>{$name}</a>";
                        $a .= "</li>";
                        return $a;
                    })->join('');
                } else {
                    $links = collect($item["links"])->map(function($item) use ($pre, $page) {
                        $a = "";
                        $url = \url::to("{$pre}/{$item["link"]}");
                        $name = $item["name"];
                        $class = "link-submenu" . ($page == $item["link"] ? " active" : "");
                        $a .= "<a class='$class' href='{$url}'>{$name}</a>";
                        return $a;
                    })->join('');
                    $class = "d-flex justify-content-between align-items-center";
                    if (str_contains($links, 'active'))
                        $class .= " active";
                    $a .= "<li class='menu__link'>";
                        $a .= "<ul class='collapse shadow-sm submenu__link' id='navbarMenu'>";
                            $a .= "<li class='p-0 position-relative'>";
                                $a .= "{$links}";
                            $a .= "</li>";
                        $a .= "</ul>";
                        $a .= "<div class='$class' data-toggle='collapse' data-target='#navbarMenu' aria-controls='navbarMenu' aria-expanded='false' aria-label='Toggle navigation'>";
                            $a .= "<a tabindex='-1' href='#'>Atención al Cliente</a>";
                            $a .= "<i class='fas ml-2 fa-caret-down'></i>";
                        $a .= "</div>";
                    $a .= "</li>";
                }

                
            } else {
                $url = isset($item["login"]) ? (\auth()->guard('web')->check() ? \url::to($item["login"][0]) : \url::to($item["link"])) : \url::to($item["link"]);
                $name = isset($item["login"]) ? (\auth()->guard('web')->check() ? $item["login"][1] : $item["name"]) : $item["name"];
                $class = "";
                if (empty($page))
                    $page = "/";
                if (!empty($page) && isset($item["link"]) && $item["link"] == $page)
                    $class = "class=active";
                $a .= "<li class='$classLI'>";
                    $a .= "<a {$class} href='{$url}'>{$name}</a>";
                $a .= "</li>";
            }
            return $a;
        })->join('');
        if (empty($classLI))
            $html = "<ul " . ($type == "footer" ? "class='footer--sitemap'" : "") . ">{$html}</ul>";

        return $html;
    }
}
