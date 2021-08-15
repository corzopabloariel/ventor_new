<?php
define("PAGINATE", 15);
define("NOTICE", "corzo.pabloariel@gmail.com");

define("MENU",
    [
        [
            "id" => "slider",
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
            "id" => "contents",
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
            "id" => "news",
            "name" => "Novedades",
            "icon" => "nav-pyrus__icon fas fa-star",
            "url" => \URL::to("adm/news")
        ], [
            "id" => "downloads",
            "name" => "Descargas",
            "icon" => "nav-pyrus__icon fas fa-download",
            "url" => \URL::to("adm/downloads")
        ], [
            "separar" => 1
        ], [
            "id" => "orders",
            "name" => "Pedidos",
            "icon" => "nav-pyrus__icon fas fa-cash-register",
            "url" => \URL::to("adm/orders")
        ], [
            "id" => "emails",
            "name" => "Emails",
            "icon" => "nav-pyrus__icon fas fa-inbox",
            "url" => \URL::to("adm/emails")
        ], [
            "id" => "clients",
            "name" => "Clientes",
            "icon" => "nav-pyrus__icon fas fa-user-tie",
            "url" => \URL::to("adm/clients")
        ], [
            "id" => "hash",
            "name" => "Hash archivos",
            "icon" => "nav-pyrus__icon fas fa-unlock-alt",
            "url" => \URL::to("adm/hashfiles")
        ]
    ]
);

define("MENU_NAV",
    [
        [
            "id" => "data",
            "name" => "Datos Ventor",
            "icon" => "fas fa-database",
            "url" => "data"
        ], [
            "id" => "users",
            "name" => "Usuarios",
            "icon" => "fas fa-user-shield",
            "url" => "users"
        ], [
            "id" => "texts",
            "name" => "Textos",
            "icon" => "fas fa-file-alt",
            "url" => "texts"
        ], [
            "id" => "configs",
            "name" => "Configuración",
            "icon" => "fas fa-wrench",
            "url" => "configs"
        ], [
            "id" => "sellers",
            "name" => "Vendedores",
            "icon" => "fas fa-portrait",
            "url" => "sellers"
        ], [
            "id" => "employees",
            "name" => "Empleados",
            "icon" => "fas fa-people-arrows",
            "url" => "employees"
        ], [
            "id" => "transports",
            "name" => "Transportes",
            "icon" => "fas fa-truck-moving",
            "url" => "transports"
        ], [
            "id" => "products",
            "name" => "Productos",
            "icon" => "fas fa-cubes",
            "url" => "products"
        ], [
            "id" => "numbers",
            "name" => "Números",
            "icon" => "fas fa-phone-square",
            "url" => "numbers"
        ], [
            "name" => "Salir",
            "icon" => "fas text-danger fa-power-off",
            "url" => "logout"
        ]
    ]
);