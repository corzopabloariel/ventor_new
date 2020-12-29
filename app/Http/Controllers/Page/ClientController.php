<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ventor\Site;
use App\Models\Client;

class ClientController extends Controller
{
    public function pedidos(Request $request)
    {
        $site = new Site("mispedidos");
        $site->setRequest($request);
        $data = $site->elements();
        return view('page.base', compact('data'));
    }

    public function datos(Request $request)
    {
        $user = \Auth::user();
        if (!$user->isShowData()) {
            return \Redirect::route('index');
        }
        $site = new Site("misdatos");
        $site->setRequest($request);
        $data = $site->elements();
        return view('page.base', compact('data'));
    }

    public function action(Request $request, String $cliente_action)
    {
        if ($cliente_action == "pedidos")
            return self::pedidos($request);
        if ($cliente_action == "mis-datos")
            return self::datos($request);
        $site = new Site("client");
        $site->setRequest($request);
        $data = $site->elements();
        if ($request->session()->has('nrocta')) {
            $client = Client::one($request->session()->get('nrocta'), "nrocta");
            $request->session()->forget('nrocta');
        } else {
            $user = \Auth::user();
            $client = $user->getClient();
        }
        $data["client"] = $client;
        $data["action"] = $cliente_action;
        $soap = null;
        if (auth()->guard('web')->check()) {
            if (auth()->guard('web')->user()->role == "ADM" || auth()->guard('web')->user()->role == "EMP")
                $data["clients"] = Client::getAll("nrocta");
            if (auth()->guard('web')->user()->role == "VND")
                $data["clients"] = Client::getAll("nrocta", "ASC", auth()->guard('web')->user()->docket);
        }
        switch ($cliente_action) {
            case "analisis-deuda":
                $data["title"] = "Análisis de deuda";
                if ($client) {
                    $soap = self::analisisDeuda($client->nrocta);
                    if ($soap) {
                        $soap = str_replace("Row", "tr", $soap);
                        $soap = str_replace("<Root>", "", $soap);
                        $soap = str_replace("</Root>", "", $soap);
                        $soap = str_replace("cod_aplicacion", "td", $soap);
                        $soap = str_replace("nro_aplicacion", "td", $soap);
                        $soap = str_replace("codigo", "td", $soap);
                        $soap = str_replace("numero", "td", $soap);
                        $soap = str_replace("cuota", "td", $soap);
                        $soap = str_replace("Cod_cliente", "td", $soap);
                        $soap = str_replace("Cliente", "td", $soap);
                        $soap = str_replace("Importe", "td", $soap);
                        $soap = str_replace("Vencimiento", "td", $soap);
                        $soap = str_replace("Emision", "td", $soap);
                        $soap = str_replace("Vendedor", "td", $soap);
                        $soap = str_replace("Comprobante", "td", $soap);
                        $soap = str_replace("<td />", "<td></td>", $soap);
                        $soap = trim($soap);
                        $soap = utf8_encode($soap);
                    }
                }
                break;
            case "faltantes":
                $data["title"] = "Faltantes";
                if ($client) {
                    $soap = self::faltantes($client->nrocta);
                    if ($soap) {
                        $soap = explode("<Row>" , $soap);
                        for($i = 0; $i < count($soap) ; $i ++) {
                            if(strpos($soap[$i], "STOCK_CENTRAL") === false)
                                $soap[$i] = str_replace("</Row>" , "<td></td></Row>", $soap[$i]);
                        }
                        $soap = implode("<Row>", $soap);
                        
                        $soap = str_replace("<Root>", "", $soap);
                        $soap = str_replace("</Root>", "", $soap);
                        $soap = str_replace("Row", "tr", $soap);
                        $soap = str_replace("CUENTA", "td", $soap);
                        $soap = str_replace("ARTICULO", "td", $soap);
                        $soap = str_replace("DESCRIPCION", "td", $soap);
                        $soap = str_replace("FECHA", "td", $soap);
                        $soap = str_replace("PRECIO", "td", $soap);
                        $soap = str_replace("CANTIDAD", "td", $soap);
                        $soap = str_replace("TOTAL", "td", $soap);
                        $soap = str_replace("STOCK_CENTRAL", "td", $soap);
                        $soap = str_replace("<td />", "<td></td>", $soap);
                        $soap = trim($soap);
                        $soap = utf8_encode($soap);
                    }
                }
                break;
            case "comprobantes":
                $data["title"] = "Comprobantes";
                if ($client) {
                    $soap = self::comprobantes($client->nrocta);
                    if ($soap) {
                        $soap = str_replace("<Root>", "", $soap);
                        $soap = str_replace("</Root>", "", $soap);
                        $soap = str_replace("Row", "tr", $soap);
                        $soap = str_replace("Modulo", "td", $soap);
                        $soap = str_replace("Codigo", "td", $soap);
                        $soap = str_replace("Numero", "td", $soap);
                        $soap = str_replace("Emision", "td", $soap);
                        $soap = str_replace("Cuenta", "td", $soap);
                        $soap = str_replace("nombre", "td", $soap);
                        $soap = str_replace("Importe", "td", $soap);
                        $soap = str_replace("<td />", "<td></td>", $soap);
                        $soap = trim($soap);
                        $soap = utf8_encode($soap);
                    }
                }
                break;
        }
        $data["soap"] = $soap;
        return view('page.base', compact('data'));
    }

    public function analisisDeuda($nrocta)
    {
        $msserver="181.170.160.91:9090";
        $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
        $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
        $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
        $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
        $param = array( "pSPName" => "Consulta_CtaCte", 'pParamList' => '$Paramnrocta;' . $nrocta , "pUserId" => "Test", "pPassword" => "c2d*-f",  "pGenLog" => "1");
        try {
            $client = new \nusoap_client('http://'.$msserver.'/dotWSUtils/WSUtils.asmx?WSDL', 'wsdl', $proxyhost , $proxyport , $proxyusername, $proxypassword );
            $result = $client->call('EjecutarSP_XML', $param, '', '', false, true);
            if ($client->fault) {
                return false;
            } else {
                $err = $client->getError();
                if ($err) {
                    return false;
                } else {
                    return $result["EjecutarSP_XMLResult"];
                }
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function faltantes($nrocta)
    {
        $msserver="181.170.160.91:9090";
        $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
        $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
        $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
        $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
        $param = array( "pSPName" => "Consulta_Faltante", 'pParamList' => '$Paramnrocta;' . $nrocta , "pUserId" => "Test", "pPassword" => "c2d*-f",  "pGenLog" => "1");
        try {
            $client = new \nusoap_client('http://'.$msserver.'/dotWSUtils/WSUtils.asmx?WSDL', 'wsdl', $proxyhost , $proxyport , $proxyusername, $proxypassword );
            $result = $client->call('EjecutarSP_XML', $param, '', '', false, true);
            
            if ($client->fault) {
                return false;
            } else {
                $err = $client->getError();
                if ($err) {
                    return false;
                } else {
                    return $result["EjecutarSP_XMLResult"];
                }
            }
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function comprobantes($nrocta)
    {
        $msserver="181.170.160.91:9090";
        $proxyhost = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
        $proxyport = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
        $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
        $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
        $param = array( "pSPName" => "Consulta_Form", 'pParamList' => '$Paramnrocta;' . $nrocta , "pUserId" => "Test", "pPassword" => "c2d*-f",  "pGenLog" => "1");
        try {
            $client = new \nusoap_client('http://'.$msserver.'/dotWSUtils/WSUtils.asmx?WSDL', 'wsdl', $proxyhost , $proxyport , $proxyusername, $proxypassword );
            $result = $client->call('EjecutarSP_XML', $param, '', '', false, true);
            
            if ($client->fault) {
                return false;
            } else {
                $err = $client->getError();
                if ($err) {
                    return false;
                } else {
                    return $result["EjecutarSP_XMLResult"];
                }
            }
        } catch (\Throwable $th) {
            return false;
        }
    }
}
