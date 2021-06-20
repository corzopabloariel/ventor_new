<?php
define("PAGINATE", 15);
define("NOTICE", "corzo.pabloariel@gmail.com");

define("MENU",
    [
        [
            "id" => "sliders",
            "name" => "Sliders",
            "icon" => "nav-pyrus__icon far fa-images",
            "urls" => [\URL::to("adm/sliders/home"), \URL::to("adm/sliders/empresa")],
            "submenu" => [
                [
                    "name" => "Home",
                    "icon" => "nav-pyrus__icon fas fa-ellipsis-h",
                    "url" => \URL::to("adm/sliders/home"),
                ], [
                    "name" => "Empresa",
                    "icon" => "nav-pyrus__icon fas fa-ellipsis-h",
                    "url" => \URL::to("adm/sliders/empresa"),
                ]
            ]
        ], [
            "id" => "contenidos",
            "name" => "Contenidos",
            "icon" => "nav-pyrus__icon fas fa-file-signature",
            "urls" => [\URL::to("adm/content/empresa"), \URL::to("adm/content/calidad")],
            "submenu" => [
                [
                    "name" => "Empresa",
                    "icon" => "nav-pyrus__icon fas fa-ellipsis-h",
                    "url" => \URL::to("adm/content/empresa"),
                ], [
                    "name" => "Calidad",
                    "icon" => "nav-pyrus__icon fas fa-ellipsis-h",
                    "url" => \URL::to("adm/content/calidad"),
                ]
            ]
        ], [
            "id" => "novedades",
            "name" => "Novedades",
            "icon" => "nav-pyrus__icon fas fa-star",
            "url" => \URL::to("adm/news")
        ], [
            "id" => "descargas",
            "name" => "Descargas",
            "icon" => "nav-pyrus__icon fas fa-download",
            "url" => \URL::to("adm/downloads")
        ], [
            "separar" => 1
        ], [
            "id" => "pedidos",
            "name" => "Pedidos",
            "icon" => "nav-pyrus__icon fas fa-cash-register",
            "url" => \URL::to("adm/orders")
        ], [
            "id" => "emails",
            "name" => "Emails",
            "icon" => "nav-pyrus__icon fas fa-inbox",
            "url" => \URL::to("adm/emails")
        ], [
            "id" => "clientes",
            "name" => "Clientes",
            "icon" => "nav-pyrus__icon fas fa-user-tie",
            "url" => \URL::to("adm/clients")
        ]
    ]
);

define("MENU_NAV",
    [
        [
            "name" => "Datos Ventor",
            "icon" => "fas fa-database",
            "url" => "data"
        ], [
            "name" => "Usuarios",
            "icon" => "fas fa-user-shield",
            "url" => "users"
        ], [
            "name" => "Textos",
            "icon" => "fas fa-file-alt",
            "url" => "texts"
        ], [
            "name" => "Configuración",
            "icon" => "fas fa-wrench",
            "url" => "configs"
        ], [
            "separar" => 1
        ], [
            "name" => "Vendedores",
            "icon" => "fas fa-portrait",
            "url" => "sellers"
        ], [
            "name" => "Empleados",
            "icon" => "fas fa-people-arrows",
            "url" => "employees"
        ], [
            "name" => "Transportes",
            "icon" => "fas fa-truck-moving",
            "url" => "transports"
        ], [
            "name" => "Productos",
            "icon" => "fas fa-cubes",
            "url" => "products"
        ], [
            "name" => "Números",
            "icon" => "fas fa-phone-square",
            "url" => "numbers"
        ], [
            "separar" => 1
        ], [
            "name" => "Salir",
            "icon" => "fas text-danger fa-power-off",
            "url" => "logout"
        ]
    ]
);