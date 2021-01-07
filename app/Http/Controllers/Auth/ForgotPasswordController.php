<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Ventor\Ticket;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nrodoc' => 'required'
        ]);
        if($validator->fails()){
            return back()->withErrors(['nrodoc' => "Dato necesario", "password" => "Complete los datos"])->withInput();
        }
        $user = User::where("username", $request->nrodoc)->first();
        if (!$user) {
            return back()->withErrors(["password" => "Dato incorrecto o no encontrado"])->withInput();
        }
        $client = $user->getClient();
        if (empty($user->email) && empty($client->direml))
            return back()->withErrors(["password" => "No se pudo encontrar un email válido"])->withInput();
        $request->request->add(['email' => $user->email]);
        $this->validateEmail($request);
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );//lunarepuestos@hotmail.com - 30616591939
        Ticket::create([
            "type" => 5,
            "table" => "users",
            "table_id" => $user->id,
            'obs' => '<p>Intento de cambio de contraseña</p>',
            'user_id' => $user->id
        ]);
        return back()->with('status', "En breve recibirá un mail con el link para reestablecer su contraseña.");
    }

    public function showLinkRequestForm(Request $request)
    {
        if (\Auth::check())
            return \Redirect::route('index');
        return \view('auth.passwords.email');
    }
}
//App\Http\Controllers\Auth\ResetPasswordController@reset
//App\Http\Controllers\Auth\ResetPasswordController@showResetForm
//App\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm
