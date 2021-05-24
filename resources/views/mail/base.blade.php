<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
	<title>{{ $subject }}</title>
	<style type="text/css">
		body {
			background: #eeeeee;
			width: 100%;
			padding: 0;
			margin: 0;
			font-size: 14px;
			color:#414141;
		}

		* {
			font-family: 'Poppins', sans-serif;
		}

		strong {
			font-family: 'Poppins', sans-serif;
			font-weight:600
		}

		.cuerpo .header {
			background: #ffffff;
			padding: 20px 10px 10px;
		}

		.cuerpo .header img {
			margin: 0 auto;
			display: block;
		}
		.cuerpo .header hr{
			border: 0;
			border-bottom: 1px solid #e5e5e5;
			margin: 20px auto 0;
			width: 90%;
		}

		.cuerpo .body {
			background: #ffffff;
			padding: 10px;
		}

		.cuerpo .resp {
			font-size:12px;
		}

		.cuerpo .footer {
			background: #999999;
			color:#ffffff;
			padding: 20px;
			font-size:12px;
		}

		.cuerpo .footer a {
			color:#ffffff;
			text-decoration: none
		}

		table.table td, table.table th {
            padding: .75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
            text-align: left;
            vertical-align: middle;
        }
        table.table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            text-transform: uppercase;
            white-space: nowrap;
            background-color: #343a40;
            color: #fff;
        }
        .price {
            white-space: nowrap;
        }
        th.image {
            width: 60px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .total {
            font-size: 25px;
		}
		.total > span:last-child() {
			float: right;
		}
        .data {
            display: flex;
            justify-content: space-between!important;
            margin-bottom: 1em;
        }
        .bg-dark {
            background-color: #343a40;
            color: #fff;
        }
        .obs:not(:empty) {
            margin-top: 1em;
        }
        .obs:not(:empty)::before {
            content: "Observaciones";
            color: #4a4a4a;
            display: block;
            text-transform: uppercase;
            font-weight: bold;
        }

		@media (max-width: 600px) {
			.cuerpo {
				margin: 10px auto;
			}
		}

	</style>
    </head>
    <body style="background: #eeeeee; width: 100%; padding: 0; margin: 0; font-size: 14px; color:#414141;">
	<table style="background-color:#eeeeee; width:100%" cellspacing="0" cellpadding="0">
		<tr>
			<td style="background-color:#eeeeee; width:100%;">
				<table class="cuerpo" cellspacing="0" style="background: #ffffff; width: 95%; max-width: 600px; margin: 40px auto; position: relative;">
                    <tr>
                        <td colspan="2" class="header" style="background: #ffffff; padding: 20px 20px 0; text-align:center">
                            <img loading="lazy" src="http://ventor.com.ar/images/empresa_images/1575909002_logo.png" alt="BuscadorProp - Logo"/>
                            <hr style="border: 0; border-bottom: 1px solid #e5e5e5; margin: 20px auto 0; width: 100%;"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0"  align="center">
                                <tr>
                                    <td style="height:50px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="font-size:33px; color:#3e3e3e; padding-bottom:1px;">{!! $welcome !!}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <p align="center" style="font-size:16px;">
                                            {{ $title }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="background: #ffffff; padding:20px; color:#414141;">
                            {!! $body !!}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="background: #ffffff; padding:20px; color:#414141;">
                        </td>
                    </tr>
					<tr>
						<td colspan="2" style="background: #e5e5e5; padding: 10px; font-size:14px; vertical-align:middle; text-align:center">
							No respondas este mail
						</td>
					</tr>
					<!-- =============================== footer ====================================== -->
					<tr>
						<td style="background: #3B3B3B; color:#ffffff; padding: 20px; font-size:14px; vertical-align:top">
							<strong>{{ config('app.name') }}</strong><br>
							Tel.: (011) 2150-2295<br>
							<a href="mailto:{{ env('MAIL_VENTOR') }}" target="_blank" style="color:#ffffff; text-decoration:none;">{{ env('MAIL_VENTOR') }}</a><br>
							<a href="https://{{ env('APP_URL') }}" target="_blank" style="color:#ffffff; text-decoration:none;">{{ env('APP_URL') }}</a>
						</td>
						<td style="background: #3B3B3B; color:#ffffff; padding: 20px; font-size:12px; vertical-align:bottom; width:120px; text-align:right">
							{{--<a href="" target="_blank" style="color:#ffffff; text-decoration:none">
								<img loading="lazy" src="https://ventor.com.ar/static/icono-facebook.png" alt="Seguínos en Facebook" style="margin-right:5px" />
							</a>--}}
							<a href="https://www.instagram.com/ventorsacei/" target="_blank" style="color:#ffffff; text-decoration:none">
								<img loading="lazy" src="https://ventor.com.ar/static/icono-instagram.png" alt="Seguínos en Instagram" />
							</a>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	
	</table>
	
</body>
</html>