<?php

namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ventor\Site;
use App\Models\Client;
use Jenssegers\Agent\Agent;

class ClientController extends Controller
{
    private $agent;
    public function __construct()
    {
        $this->agent = new Agent();
    }
    public function pedidos(Request $request)
    {
        $site = new Site("mispedidos");
        $site->setRequest($request);
        $data = $site->elements();
        if ($this->agent->isDesktop())
            return view('page.base', compact('data'));
        return view('page.mobile', compact('data'));
    }

    public function datos(Request $request)
    {
        $user = \Auth::user();
        if (!$user->isShowData() && !session()->has('accessADM')) {
            return \Redirect::route('index');
        }
        $site = new Site("misdatos");
        $site->setRequest($request);
        $data = $site->elements();
        $data["client"] = session()->has('accessADM') ? session()->get('accessADM')->getClient() : $user->getClient();

        if ($this->agent->isDesktop())
            return view('page.base', compact('data'));
        return view('page.mobile', compact('data'));
    }

    public function action(Request $request, String $cliente_action)
    {
        $user = \Auth::user();
        if ($cliente_action == "mis-pedidos")
            return self::pedidos($request);
        if ($user->test) {
            return \Redirect::route('index');
        }
        if ($cliente_action == "mis-datos")
            return self::datos($request);
        $site = new Site("client");
        $site->setRequest($request);
        $data = $site->elements();
        if ($request->session()->has('nrocta')) {
            $client = Client::one($request->session()->get('nrocta'), "nrocta");
            $request->session()->forget('nrocta');
        } else {
            $user = session()->has('accessADM') ? session()->get('accessADM') : \Auth::user();
            $client = $user->getClient();
        }
        $data["client"] = $client;
        $data["action"] = $cliente_action;
        $soap = null;
        if (auth()->guard('web')->check() && !session()->has('accessADM')) {
            if (auth()->guard('web')->user()->role == "ADM" || auth()->guard('web')->user()->role == "EMP")
                $data["clients"] = Client::getAll("nrocta");
            if (auth()->guard('web')->user()->role == "VND") {
                if (empty(!auth()->guard('web')->user()->dockets))
                    $data["clients"] = Client::getAll("nrocta", "ASC", auth()->guard('web')->user()->docket);
                else
                    $data["clients"] = Client::whereIn("vendedor.code", auth()->guard('web')->user()->dockets)->orderBy("nrocta", "ASC")->get();
            }
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
                    if (!empty($soap)) {
                        $soap = str_replace("</tr>", "", $soap);
                        $soap = str_replace("</td>", "", $soap);
                        $soap = explode("<tr>", $soap);
                        $soap = collect($soap)->map(function($item) {
                            if (empty($item))
                                return null;
                            try {
                                $item = explode("<td>", $item);
                                $item = array_map("self::clean", $item);
                                $item = array_diff($item, array("", null));
                                $item = array_values($item);
                                $name = "VT{$item[2]}{$item[3]}.PDF";
                                $item[] = "<a class='btn btn-danger' target='blank' href='http://181.15.104.2/comprobantes/{$name}'><i class='fas fa-file-pdf'></i></a>";
                                $item = array_combine(["aplicacion", "nroAplicacion", "codigo", "numero", "cuota", "codCliente", "cliente", "importe", "vencimiento", "emision", "vendedor", "comprobante", "pdf"], $item);
                                $item["importeNumber"] = floatval($item["importe"]);
                                $item["importe"] = ($item["importeNumber"] < 0) ? "-$ " . number_format($item["importeNumber"] * -1, 2, ",", ".") : "$ " . number_format($item["importeNumber"], 2, ",", ".");
                                return $item;
                            } catch (\Throwable $th) {
                                return null;
                            }
                        })->filter(function($value, $key) {
                            return !empty($value);
                        })->toArray();
                        $total = collect($soap)->map(function($item) {
                            return $item["importeNumber"];
                        })->sum();
                        $soap = ["soap" => $soap, "total" => $total];
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
                if (!empty($soap)) {
                    $soap = str_replace("</tr>", "", $soap);
                    $soap = str_replace("</td>", "", $soap);
                    $soap = explode("<tr>", $soap);
                    $soap = collect($soap)->map(function($item) {
                        if (empty($item))
                            return null;
                        try {
                            $item = explode("<td>", $item);
                            $item = array_map("self::clean", $item);
                            $item = array_diff($item, array("", null));
                            $item = array_values($item);
                            $item = array_combine(["cuenta", "articulo", "descripcion", "fecha", "precio", "cantidad", "total", "stock"], $item);
                            $item["precio"] = "$ " . number_format($item["precio"], 2, ",", ".");
                            $item["total"] = "$ " . number_format($item["total"], 2, ",", ".");
                            $item["cantidad"] = intval($item["cantidad"]);
                            $item["stock"] = intval($item["stock"]);
                            return $item;
                        } catch (\Throwable $th) {
                            return null;
                        }
                    })->filter(function($value, $key) {
                        return !empty($value);
                    })->toArray();
                    $soap = ["soap" => $soap];
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
                if (!empty($soap)) {
                    $soap = str_replace("</tr>", "", $soap);
                    $soap = str_replace("</td>", "", $soap);
                    $soap = explode("<tr>", $soap);
                    $soap = collect($soap)->map(function($item) {
                        if (empty($item))
                            return null;
                        try {
                            $item = explode("<td>", $item);
                            $item = array_map("self::clean", $item);
                            $item = array_diff($item, array("", null));
                            $item = array_values($item);
                            $name = "{$item[0]}{$item[1]}{$item[2]}.PDF";
                            $item[] = "<a class='btn btn-danger' target='blank' href='http://181.15.104.2/comprobantes/{$name}'><i class='fas fa-file-pdf'></i></a>";
                            $item = array_combine(["modulo", "codigo", "numero", "emision", "cuenta", "nombre", "importe", "pdf"], $item);
                            $item["importeNumber"] = floatval($item["importe"]);
                            $item["importe"] = ($item["importeNumber"] < 0) ? "-$ " . number_format($item["importeNumber"] * -1, 2, ",", ".") : "$ " . number_format($item["importeNumber"], 2, ",", ".");
                            return $item;
                        } catch (\Throwable $th) {
                            return null;
                        }
                    })->filter(function($value, $key) {
                        return !empty($value);
                    })->toArray();
                    $total = collect($soap)->map(function($item) {
                        return $item["importeNumber"];
                    })->sum();
                    $soap = ["soap" => $soap, "total" => $total];
                }
                break;
        }
        $data["soap"] = $soap;
        if ($this->agent->isDesktop())
            return view('page.base', compact('data'));
        return view('page.mobile', compact('data'));
    }

    public function clean($n)
    {
        return trim($n);
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
