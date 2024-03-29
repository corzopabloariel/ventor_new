@php
date_default_timezone_set('America/Argentina/Buenos_Aires');
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial;
            font-size: 14px;
        }
        hr {
            margin: 1em 0;
        }
        .break {
            display:block;
            page-break-before:always;
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-spacing: 0;
        }
        .ml-2 {
            margin-left: 20px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .table th, .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }
        .table .thead-dark th {
            color: #ffffff;
            background-color: #343a40;
            border-color: #454d55;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body>
    <p class="text-right"><small>{{ date('d/m/Y H:i:s') }}</small></p>
    <hr>
    {!! $html !!}
    <script>
        window.addEventListener("load", function(event) {
            window.print();
        });
        window.onafterprint = function(event) {
            window.close();
        };
    </script>
</body>
</html>