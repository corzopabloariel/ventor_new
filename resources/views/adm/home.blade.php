@push('js')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="{{ asset('js/alertify.js') }}"></script>
<script src="{{ asset('js/shortcut.js') }}"></script>
<script src="{{ asset('js/pyrus.js') . '?t=' . time() }}"></script>
<script src="{{ asset('js/basic.js') . '?t=' . time() }}"></script>
<script>
const generateFile = function(type) {
    $("#notification").removeClass("d-none").addClass("d-flex");
    $("#notification .notification--text").text("En proceso");
    Connect.one(`${url_simple+url_basic}export/${type}`, response => {
        let {data} = response;
        $("#notification").removeClass("d-flex").addClass("d-none");
        $("#notification .notification--text").text("");
        if (data.error === 0) {
            Toast.fire({
                icon: 'success',
                title: 'Archivo generado'
            });
            setTimeout(() => {
                location.reload()
            }, 2500);
        } else {
            Toast.fire({
                icon: 'error',
                title: data.message
            });
        }
    }, err => {
        Toast.fire({
            icon: 'error',
            title: 'Revisar consola'
        });
        console.error(err);
        $("#notification").removeClass("d-flex").addClass("d-none");
        $("#notification .notification--text").text("");
    });
};
const generateFileFunction = function(type) {
    Swal.fire({
        title: "Atención!",
        text: `Esta por generar la lista de precios en formato ${type.toUpperCase()}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',

        confirmButtonText: '<i class="fas fa-check"></i> Confirmar',
        confirmButtonAriaLabel: 'Confirmar',
        cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
        cancelButtonAriaLabel: 'Cancelar'
    }).then(result => {
        if (result.value) {
            Toast.fire({
                icon: 'warning',
                title: 'Espere'
            });
            generateFile(type);
        }
    });
};
const uploadProducts = function() {
    $("#notification").removeClass("d-none").addClass("d-flex");
    $("#notification .notification--text").text("En proceso");
    Connect.one(`${url_simple+url_basic}products/load`, data => {
        'use strict'
        $("#notification").removeClass("d-flex").addClass("d-none");
        $("#notification .notification--text").text("");
        if (data.data.error === 0) {
            Toast.fire({
                icon: 'success',
                title: data.data.message
            });
            setTimeout(() => {
                location.reload(data.url_search)
            }, 2000);
        } else {
            Toast.fire({
                icon: 'error',
                title: data.data.message
            });
        }
    }, err => {
        Toast.fire({
            icon: 'error',
            title: 'Revisar consola'
        });
        console.error(err);
        $("#notification").removeClass("d-flex").addClass("d-none");
        $("#notification .notification--text").text("");
    });
};
const actualizarApplicationsFunction = function(t) {
    Swal.fire({
        title: "Atención!",
        text: "Esta por actualizar las \"Aplicaciones\"",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',

        confirmButtonText: '<i class="fas fa-check"></i> Confirmar',
        confirmButtonAriaLabel: 'Confirmar',
        cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
        cancelButtonAriaLabel: 'Cancelar'
    }).then(result => {
        if (result.value) {
            Toast.fire({
                icon: 'warning',
                title: 'Espere'
            });
            $("#notification").removeClass("d-none").addClass("d-flex");
            $("#notification .notification--text").text("En proceso");
            Connect.one(`${url_simple+url_basic}application/load`, data => {
                'use strict'
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
                if (data.data.error === 0) {
                    Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
                    setTimeout(() => {
                        location.reload(data.url_search)
                    }, 2000);
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.data.message
                    });
                }
            }, err => {
                Toast.fire({
                    icon: 'error',
                    title: 'Revisar consola'
                });
                console.error(err);
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
            });
        }
    });
};
const actualizarProductsFunction = function(t) {
    Swal.fire({
        title: "Atención!",
        text: "Esta por actualizar los datos de \"Productos\"",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',

        confirmButtonText: '<i class="fas fa-check"></i> Confirmar',
        confirmButtonAriaLabel: 'Confirmar',
        cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
        cancelButtonAriaLabel: 'Cancelar'
    }).then(result => {
        if (result.value) {
            Toast.fire({
                icon: 'warning',
                title: 'Espere'
            });
            uploadProducts();
        }
    });
};
const actualizarClientsFunction = function(t) {
    Swal.fire({
        title: "Atención!",
        text: "Esta por actualizar los datos de \"Clientes\"",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',

        confirmButtonText: '<i class="fas fa-check"></i> Confirmar',
        confirmButtonAriaLabel: 'Confirmar',
        cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
        cancelButtonAriaLabel: 'Cancelar'
    }).then(result => {
        if (result.value) {
            Toast.fire({
                icon: 'warning',
                title: 'Espere'
            });
            $("#notification").removeClass("d-none").addClass("d-flex");
            $("#notification .notification--text").text("En proceso");
            Connect.one(`${url_simple+url_basic}clients/load`, data => {
                'use strict'
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
                if (data.data.error === 0) {
                    Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.data.message
                    });
                }
            }, err => {
                Toast.fire({
                    icon: 'error',
                    title: 'Revisar consola'
                });
                console.error(err);
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
            });
        }
    });
};
const actualizarEmployeesFunction = function(t) {
    Swal.fire({
        title: "Atención!",
        text: "Esta por actualizar los datos de \"Empleados\"",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',

        confirmButtonText: '<i class="fas fa-check"></i> Confirmar',
        confirmButtonAriaLabel: 'Confirmar',
        cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
        cancelButtonAriaLabel: 'Cancelar'
    }).then(result => {
        if (result.value) {
            Toast.fire({
                icon: 'warning',
                title: 'Espere'
            });
            $("#notification").removeClass("d-none").addClass("d-flex");
            $("#notification .notification--text").text("En proceso");
            Connect.one(`${url_simple+url_basic}employees/load`, data => {
                'use strict'
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
                if (data.data.error === 0) {
                    Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.data.message
                    });
                }
            }, err => {
                Toast.fire({
                    icon: 'error',
                    title: 'Revisar consola'
                });
                console.error(err);
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
            });
        }
    });
};
const actualizarSellersFunction = function(t) {
    Swal.fire({
        title: "Atención!",
        text: "Esta por actualizar los datos de \"Vendedores\"",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',

        confirmButtonText: '<i class="fas fa-check"></i> Confirmar',
        confirmButtonAriaLabel: 'Confirmar',
        cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
        cancelButtonAriaLabel: 'Cancelar'
    }).then(result => {
        if (result.value) {
            Toast.fire({
                icon: 'warning',
                title: 'Espere'
            });
            Connect.one(`${url_simple+url_basic}sellers/load`, data => {
                'use strict'
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
                if (data.data.error === 0) {
                    Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.data.message
                    });
                }
            }, err => {
                Toast.fire({
                    icon: 'error',
                    title: 'Revisar consola'
                });
                console.error(err);
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
            });
        }
    });
};
const actualizarTransportsFunction = function(t) {
    Swal.fire({
        title: "Atención!",
        text: "Esta por actualizar los datos de \"Transportes\"",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',

        confirmButtonText: '<i class="fas fa-check"></i> Confirmar',
        confirmButtonAriaLabel: 'Confirmar',
        cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
        cancelButtonAriaLabel: 'Cancelar'
    }).then(result => {
        if (result.value) {
            Toast.fire({
                icon: 'warning',
                title: 'Espere'
            });
            $("#notification").removeClass("d-none").addClass("d-flex");
            $("#notification .notification--text").text("En proceso");
            Connect.one(`${url_simple+url_basic}transports/load`, data => {
                'use strict'
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
                if (data.data.error === 0) {
                    Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.data.message
                    });
                }
            }, err => {
                Toast.fire({
                    icon: 'error',
                    title: 'Revisar consola'
                });
                console.error(err);
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
            });
        }
    });
};
const actualizarTodoFunction = function(t) {
    Swal.fire({
        title: "Atención!",
        text: "Esta por actualizar TODO",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',

        confirmButtonText: '<i class="fas fa-check"></i> Confirmar',
        confirmButtonAriaLabel: 'Confirmar',
        cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
        cancelButtonAriaLabel: 'Cancelar'
    }).then(result => {
        if (result.value) {
            Toast.fire({
                icon: 'warning',
                title: 'Espere'
            });
            $("#notification").removeClass("d-none").addClass("d-flex");
            $("#notification .notification--text").text("En proceso");
            Connect.one(`${url_simple+url_basic}transports/load`, data => {
                'use strict'
                if (data.data.error === 0) {
                    Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.data.message
                    });
                }
            }, err => {
                Toast.fire({
                    icon: 'error',
                    title: 'Revisar consola'
                });
                console.error(err);
                $("#notification").removeClass("d-flex").addClass("d-none");
                $("#notification .notification--text").text("");
            });
            ////////////
            Connect.one(`${url_simple+url_basic}sellers/load`, data => {
                'use strict'
                if (data.data.error === 0) {
                    Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.data.message
                    });
                }
            }, err => {
                Toast.fire({
                    icon: 'error',
                    title: 'Revisar consola'
                });
                console.error(err);
            });
            ////////////
            Connect.one(`${url_simple+url_basic}employees/load`, data => {
                'use strict'
                if (data.data.error === 0) {
                    Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.data.message
                    });
                }
            }, err => {
                Toast.fire({
                    icon: 'error',
                    title: 'Revisar consola'
                });
                console.error(err);
            });
            /////////////
            Connect.one(`${url_simple+url_basic}clients/load`, data => {
                'use strict'
                if (data.data.error === 0) {
                    Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.data.message
                    });
                }
            }, err => {
                Toast.fire({
                    icon: 'error',
                    title: 'Revisar consola'
                });
                console.error(err);
            });
            ////////////
            Connect.one(`${url_simple+url_basic}products/load`, data => {
                'use strict'
                if (data.data.error === 0) {
                    Toast.fire({
                        icon: 'success',
                        title: data.data.message
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.data.message
                    });
                }
            }, err => {
                Toast.fire({
                    icon: 'error',
                    title: 'Revisar consola'
                });
                console.error(err);
            });
            //////////////////////
            //////////////////////
            Connect.one(`${url_simple+url_basic}export/xls`, response => {
                let {data} = response;
                if (data.error === 0) {
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.message
                    });
                }
            }, err => {});
            Connect.one(`${url_simple+url_basic}export/dbf`, response => {
                let {data} = response;
                if (data.error === 0) {
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.message
                    });
                }
            }, err => {});
            Connect.one(`${url_simple+url_basic}export/txt`, response => {
                let {data} = response;
                if (data.error === 0) {
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.message
                    });
                }
            }, err => {});
            Connect.one(`${url_simple+url_basic}export/csv`, response => {
                let {data} = response;
                if (data.error === 0) {
                    $("#notification").removeClass("d-flex").addClass("d-none");
                    $("#notification .notification--text").text("");

                    setTimeout(() => {
                        location.reload()
                    }, 2500);
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: data.message
                    });
                }
            }, err => {});

        }
    });
}
const actualizarTxtProductsFunction = function(t) {
    $("#modalProduct").modal("show");
};
const uploadFile = function(t) {
    Toast.fire({
        icon: 'warning',
        title: 'Espere'
    });
    let formData = new FormData(t);
    axios({
        method: t.method,
        url: t.action,
        data: formData,
        responseType: 'json',
        config: { headers: {'Content-Type': 'multipart/form-data' }}
    })
    .then(res => {
        $("#inputGroupFile").val();
        if (res.data.error === 0) {
            $("#modalProduct").modal("hide");
            Toast.fire({
                icon: 'success',
                title: res.data.msg
            });
            if (res.data.update === 1)
                uploadProducts();
        } else {
            Toast.fire({
                icon: 'error',
                title: res.data.msg
            });
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire(
            'Atención',
            'Error al subir el archivo',
            'error'
        );
    });
};
</script>
@endpush
@push('modal')
@php
$file = configs("FILE_PRODUCTS", env('FILE_PRODUCTS'));
$filename = implode('/', [public_path(), env('FOLDER_TXT'), $file]);
$stringFile = public_path() . "/file/log_update.txt";
$lastUpdate = "-";
if (file_exists($stringFile)) {
    $logFile = fopen($stringFile, "r") or die("Unable to open file!");
    $lastUpdate = fread($logFile,filesize(public_path() . "/file/log_update.txt"));
    $lastUpdate = date("d/m/Y H:i:s", strtotime($lastUpdate));
    fclose($logFile);
}

$applications = configs("EXCEL_APLICACIONES");
if (!empty($applications)) {
    $applications = explode('|', $applications);
    $applications = collect($applications)->map(function($document) {
        list($name, $file, $active) = explode('=', $document);
        if (file_exists("/var/www/pedidos/file/{$file}")) {
            return "<p>{$name} - {$file}<i class='ml-2 ".($active == 1 ? "text-success" : "text-danger")." fas fa-file-excel'></i></p>";
        }
        return "";
    })->join('');
}
@endphp
<!-- Modal -->
<div class="modal fade" id="modalProduct" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="modalProductLabel">Actualizar archivo de productos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('ventor.product.file') }}" onsubmit="event.preventDefault(); uploadFile(this);" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <h1 class="text-center">¡¡Atención!!</h1>
                    <p>Se reemplazará el contenido del archivo <strong>{{$file}}</strong></p>
                    <div class="mt-3">
                        <label for="inputGroupFile">Seleccione archivo</label>
                        <input id="inputGroupFile" class="form-control" type="file" name="file" accept=".txt">
                    </div>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="update" value="1" id="update">
                        <label class="form-check-label" for="update">
                            Actualizar productos al terminar de subir el archivo?
                        </label>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" type="submit">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
<section class="my-3">
    <div class="container-fluid">
        <div class="p-5 bg-white">
            <h1 class="text-center text-welcome">Bienvenido {{Auth::user()->name}}</h1>
            @if (Auth::user()->isAdmin())
            <p class="text-right mb-0 mt-2 text-muted">Última actualización de los registros: <strong>{{ $lastUpdate }}</strong></p>
            @endif
        </div>
        @php
        $permissions = Auth::user()->permissions;
        @endphp
        @if (empty($permissions) || (isset($permissions['products']) && $permissions['products']['update'] || isset($permissions['clients']) && $permissions['clients']['update'] || isset($permissions['employees']) && $permissions['employees']['update'] || isset($permissions['sellers']) && $permissions['sellers']['update'] || isset($permissions['transports']) && $permissions['transports']['update']))
        <div class="p-5 bg-white mt-3">
            @if (empty($permissions) || isset($permissions['products']) && $permissions['products']['update'])
            <button type="button" onclick="actualizarProductsFunction();" class="btn btn-lg btn-primary">Actualizar productos</button>
            @endif
            @if (empty($permissions) || isset($permissions['clients']) && $permissions['clients']['update'])
            <button type="button" onclick="actualizarClientsFunction();" class="btn btn-lg btn-info">Actualizar clientes</button>
            @endif
            @if (empty($permissions) || isset($permissions['employees']) && $permissions['employees']['update'])
            <button type="button" onclick="actualizarEmployeesFunction();" class="btn btn-lg btn-success">Actualizar empleados</button>
            @endif
            @if (empty($permissions) || isset($permissions['sellers']) && $permissions['sellers']['update'])
            <button type="button" onclick="actualizarSellersFunction();" class="btn btn-lg btn-danger">Actualizar vendedores</button>
            @endif
            @if (empty($permissions) || isset($permissions['transports']) && $permissions['transports']['update'])
            <button type="button" onclick="actualizarTransportsFunction();" class="btn btn-lg btn-warning">Actualizar transportes</button>
            @endif
            @if (empty($permissions)
                || isset($permissions['products']) && $permissions['products']['update']
                || isset($permissions['clients']) && $permissions['clients']['update']
                || isset($permissions['employees']) && $permissions['employees']['update']
                || isset($permissions['sellers']) && $permissions['sellers']['update']
                || isset($permissions['transports']) && $permissions['transports']['update']
            )
            <button type="button" onclick="actualizarTodoFunction();" class="btn btn-lg btn-dark">Actualizar TODO</button>
            @endif
            @if (Auth::user()->isAdmin())
            <hr>
            <button type="button" onclick="actualizarTxtProductsFunction();" class="btn btn-lg btn-primary">Actualizar TXT productos</button>
            <hr>
            <div class="row">
                <div class="col-12 col-md-6">
                    <h3>Usuario de prueba @if($data["prueba"])<i class="fas fa-check-circle text-success"></i>@else<i class="fas fa-times-circle text-danger"></i>@endif</h3>
                    <form class="mt-3" action="" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="">Nombre<small class="fas fa-star-of-life ml-1"></small></label>
                                    @php
                                    $value = "";
                                    if($data["prueba"])
                                        $value = $data["prueba"]->name;
                                    if (!empty(old('name')))
                                        $value = old('name');
                                    @endphp
                                    <input value="{{ $value }}" type="text" required name="name" placeholder="Nombre" class="form-control form-control-lg">
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="form-group">
                                    <label for="">Descargas p/hora</label>
                                    @php
                                    $value = "";
                                    if ($data["prueba"] && !empty($data["prueba"]->limit))
                                        $value = $data["prueba"]->limit;
                                    if (!empty(old('limit')))
                                        $value = old('limit');
                                    @endphp
                                    <input value="{{ $value }}" type="number" name="limit" min="0" placeholder="Límite de descargas" class="form-control form-control-lg">
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="form-group">
                                    <label for="">Usuario<small class="fas fa-star-of-life ml-1"></small></label>
                                    @php
                                    $value = "";
                                    if($data["prueba"])
                                        $value = $data["prueba"]->username;
                                    if (!empty(old('username')))
                                        $value = old('username');
                                    @endphp
                                    <input value="{{ $value }}" type="text" required name="username" placeholder="Usuario" class="form-control form-control-lg">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="">Contraseña<small class="fas fa-star-of-life ml-1"></small></label>
                                    <input type="password" name="password" placeholder="Contraseña" class="form-control form-control-lg">
                                    <small class="form-text text-muted">Solo completar en caso de blanquear contraseña.</small>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="">Email</label>
                                    @php
                                    $value = "";
                                    if ($data["prueba"] && !empty($data["prueba"]->email))
                                        $value = $data["prueba"]->email;
                                    if (!empty(old('email')))
                                        $value = old('email');
                                    @endphp
                                    <input value="{{ $value }}" type="email" name="email" placeholder="Email" class="form-control form-control-lg">
                                    <small class="form-text text-muted">Solo completar en caso de querer recibir copia del pedido.</small>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-dark">Cambiar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-12 col-md-6">
                    <h3 class="mb-3">Lista de precios general</h3>
                    @php
                    $files = [
                        ['icon' => 'fas fa-file-alt mr-2', 'name' => 'VENTOR LISTA DE PRECIOS FORMATO TXT.txt', 'file' => asset('/file/VENTOR LISTA DE PRECIOS FORMATO TXT.txt'), 'storage' => storage_path().'/app/public/file/VENTOR LISTA DE PRECIOS FORMATO TXT.txt'],
                        ['icon' => 'fas fa-file mr-2', 'name' => 'VENTOR LISTA DE PRECIOS FORMATO DBF.dbf', 'file' => asset('/file/VENTOR LISTA DE PRECIOS FORMATO DBF.dbf'), 'storage' => storage_path().'/app/public/file/VENTOR LISTA DE PRECIOS FORMATO DBF.dbf'],
                        ['icon' => 'fas fa-file-excel mr-2', 'name' => 'VENTOR LISTA DE PRECIOS FORMATO XLS.xls', 'file' => asset('/file/VENTOR LISTA DE PRECIOS FORMATO XLS.xls'), 'storage' => storage_path().'/app/public/file/VENTOR LISTA DE PRECIOS FORMATO XLS.xls'],
                        ['icon' => 'fas fa-file-excel mr-2', 'name' => 'VENTOR LISTA DE PRECIOS FORMATO CSV.csv', 'file' => asset('/file/VENTOR LISTA DE PRECIOS FORMATO CSV.csv'), 'storage' => storage_path().'/app/public/file/VENTOR LISTA DE PRECIOS FORMATO CSV.csv']
                    ];
                    @endphp
                    @foreach($files AS $file)
                        @if (file_exists($file['storage']))
                        <p>
                            <a download href="{{$file['file']}}">
                                <i class="{{$file['icon']}} text-success"></i>{{$file['name']}}
                            </a>
                            <small>{{ date("d/m/Y H:i:s", filemtime($file['storage'])) }}</small>
                        </p>
                        @else
                        <p><i class="{{$file['icon']}} text-danger"></i>{{$file['name']}}</p>
                        @endif
                    @endforeach
                    <div class="mt-3">
                        <p>Subir imagen por FTP<br/><strong>Ruta:</strong> <i>/public_html/staticbcp/img/lista_precios_general.jpg</i><br/><strong>Link:</strong> <a href="{{config('app.static')}}img/lista_precios_general.jpg" target="_blank">{{config('app.static')}}img/lista_precios_general.jpg <i class="fas fa-file-image text-primary"></i></a></p>
                    </div>
                    <hr>
                    <h3 class="mb-3">Emails de pedidos</h3>
                    @php
                    $emails = configs('EMAILS_ORDER');
                    @endphp
                    <p>{{ $emails }}</p>
                    <p><small>Para modificar debe ir a <a class="text-dark" href="{{ URL::to('adm/configs') }}">Configuración y cambiar el valor de <strong class="text-primary">EMAILS_ORDER</strong></a>, separando cada email con <strong>;</strong></small></p>
                    <p><small>Si un pedido es de prueba, no se incluirá <span class="text-primary">pedidos.ventor@gmx.com</span></small></p>
                    <hr>
                    @if (!empty($applications))
                    <button type="button" onclick="actualizarApplicationsFunction();" class="btn btn-lg btn-info">Actualizar aplicaciones</button>
                    @endif
                    {!! $applications !!}
                </div>
            </div>
            @endif
            <br/>
            @if (empty($permissions) || isset($permissions['products']) && $permissions['products']['update'])
            <button type="button" onclick="generateFileFunction('xls');" class="btn btn-lg btn-success">Generar XLS</button>
            <button type="button" onclick="generateFileFunction('dbf');" class="btn btn-lg btn-success">Generar DBF</button>
            <button type="button" onclick="generateFileFunction('txt');" class="btn btn-lg btn-success">Generar TXT</button>
            <button type="button" onclick="generateFileFunction('csv');" class="btn btn-lg btn-success">Generar CSV</button>
            @endif
        </div>
        @endif
    </div>
</section>