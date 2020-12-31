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

		.cuerpo .inmobiliaria {
			font-weight:700;
			font-size:18px;
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

		.iconoMail {
			margin: 20px auto 10px;
		}

		.propiedad {
			position: relative;
			width: 90%;
			margin: 10px auto 20px;
		}

		.propiedad .foto{
			width: 200px;
			min-height: 150px;
			background-repeat: no-repeat;
			background-size: cover;
			background-position: center center;
			margin: 0;
			padding: 0;
		}

		.propiedad .foto a {
			width: 100%;
			height: 150px;
			display: block;
		}

		.propiedad .datos{
			border:1px solid #e5e5e5;
			background: #ffffff;
			padding: 15px;
			vertical-align: top
		}

		.propiedad .datos a {
			position: absolute;
			width: 100%;
			height: 100%;
			top: 0;
			left: 0;
			display: block;
			z-index: 10;
		}

		.propiedad .datos .direccion {
			font-size:16px;
			font-weight:700;
		}

		.propiedad .datos .localidad {
			font-size:12px;
			font-weight:700;
			display: block;
			margin-bottom: 5px;
		}

		.propiedad .datos .operacion {
			font-weight:500;
			font-size:12px;
			display: block;
			margin: 0 0 2px;
		}

		.propiedad .datos .titulo {
			font-weight: 700;
			font-size: 12px;
			display: block;
			margin: 5px 0 0;
		}

		.propiedad .datos .codigo {
			font-weight:500;
			font-size:12px;
			display: block;
			color:#787878
		}


		.interesado {
			position: relative;
			width: 90%;
			margin: 10px auto 20px;
			background: #ffffff;
		}


		.interesado .datos .busco a {
			font-weight:500;
			font-size:14px;
			color:#333333;
			text-decoration: underline
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

		@media (max-width: 600px) {
			.cuerpo {
				margin: 10px auto;
			}

			.propiedad .foto {
				display: block;
				margin: 0 auto 10px;
			}

			.propiedad .datos {
				display: block;
				margin: 0 auto;
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
							<strong>{{ env('APP_NAME') }}</strong><br>
							Tel.: (011) 2150-2295<br>
							<a href="mailto:{{ env('MAIL_VENTOR') }}" target="_blank" style="color:#ffffff; text-decoration:none;">{{ env('MAIL_VENTOR') }}</a><br>
							<a href="https://{{ env('APP_URL') }}" target="_blank" style="color:#ffffff; text-decoration:none;">{{ env('APP_URL') }}</a>
						</td>
						<td style="background: #3B3B3B; color:#ffffff; padding: 20px; font-size:12px; vertical-align:bottom; width:120px; text-align:right">
							{{--<a href="" target="_blank" style="color:#ffffff; text-decoration:none">
								<img loading="lazy" src="https://ventor.com.ar/images/static/icono-facebook.png" alt="Seguínos en Facebook" style="margin-right:5px" />
							</a>--}}
							<a href="https://www.instagram.com/ventorsacei/" target="_blank" style="color:#ffffff; text-decoration:none">
								<img loading="lazy" src="https://ventor.com.ar/images/static/icono-instagram.png" alt="Seguínos en Instagram" />
							</a>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	
	</table>
	
</body>
</html>