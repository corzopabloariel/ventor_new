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
            "urls" => [\URL::to("root"), \URL::to("root/helps"), \URL::to("root/labels")],
            "submenu" => [
                [
                    "name" => "Home",
                    "icon" => "nav-pyrus__icon fas fa-ellipsis-h",
                    "url" => \URL::to("root/helps"),
                ], [
                    "name" => "Empresa",
                    "icon" => "nav-pyrus__icon fas fa-ellipsis-h",
                    "url" => \URL::to("root/labels"),
                ], [
                    "name" => "Calidad",
                    "icon" => "nav-pyrus__icon fas fa-ellipsis-h",
                    "url" => \URL::to("root/labels"),
                ]
            ]
        ], [
            "id" => "novedades",
            "name" => "Novedades",
            "icon" => "nav-pyrus__icon fas fa-star",
            "url" => \URL::to("root/operations")
        ], [
            "id" => "descargas",
            "name" => "Descargas",
            "icon" => "nav-pyrus__icon fas fa-download",
            "url" => \URL::to("root/operations")
        ], [
            "separar" => 1
        ], [
            "id" => "pedidos",
            "name" => "Pedidos",
            "icon" => "nav-pyrus__icon fas fa-cash-register",
            "url" => \URL::to("root/users")
        ], [
            "id" => "clientes",
            "name" => "Clientes",
            "icon" => "nav-pyrus__icon fas fa-user-tie",
            "url" => \URL::to("root/clients")
        ], [
            "id" => "actualizar",
            "name" => "Actualizar",
            "icon" => "nav-pyrus__icon fas fa-sync",
            "url" => \URL::to("root/forms")
        ]
    ]
);

define("MENU_NAV",
    [
        [
            "name" => "Mis datos",
            "icon" => "fas fa-database",
            "url" => "data"
        ], [
            "name" => "Usuarios",
            "icon" => "fas fa-user-shield",
            "url" => "users"
        ], [
            "name" => "Logs",
            "icon" => "far fa-list-alt",
            "url" => "logs"
        ], [
            "separar" => 1
        ], [
            "name" => "Clientes",
            "icon" => "fas fa-suitcase",
            "url" => "clients"
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