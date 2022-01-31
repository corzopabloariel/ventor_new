<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\Ventor\Cart;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(Request $request, $role)
    {
        return view('auth.login', compact('role'));
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    public function login(Request $request, $role)
    {
        $requestData = $request->except(['_token']);
        if (strpos($request->username, ":")) {
            list($u, $p) = explode(":", $request->username);
            $request->request->set('username', $u);
            $request->request->add(['password' => $p]);
            $requestData = $request->except(['_token']);
        } else {
            if ($role != "client" || strpos($request->username, "EMP_") !== false || strpos($request->username, "VND_") !== false)
                $request->request->add(['password' => config('app.pass')]);
            $requestData = $request->except(['_token']);
        }

        /*$this->validateLogin($request);
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }*/
        //$fieldType = filter_var($request->username, FILTER_VALIDATE_INT) ? 'docket' : 'username';
        if(auth()->attempt(array('username' => $requestData['username'], 'password' => $requestData['password'])) ||
            auth()->attempt(array('docket' => $requestData['username'], 'password' => $requestData['password'])))
        {

            session(['role' => Auth::user()->role]);
            session(['cartSelect' => '1']);
            if (!\Auth::user()->isShowQuantity()) {
                \Auth::user()->setConfig([
                    'other' => ['secret' => $requestData['password']]
                ]);
            }
            return response(
                array(
                    'error'     => false,
                    'status'    => 202
                )
            );

        } else {

            return response(
                array(
                    'error'     => false,
                    'status'    => 202,
                    'message'   => 'Datos incorrectos o no encontrados'
                )
            );

        }
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        return redirect($user->redirect());
    }

    public function logout(Request $request)
    {
        if ($request->session()->has('cartSelect')) {
            $request->session()->forget('cartSelect');
        }

        if ($request->session()->has('cart')) {
            $request->session()->forget('cart');
        }
        if ($request->session()->has('markup')) {
            $request->session()->forget('markup');
        }
        if ($request->session()->has('type')) {
            $request->session()->forget('type');
        }
        if ($request->session()->has('nrocta_client')) {
            $request->session()->forget('nrocta_client');
        }
        if ($request->session()->has('nrocta')) {
            $request->session()->forget('nrocta');
        }
        if ($request->session()->has('accessADM')) {
            $request->session()->forget('accessADM');
        }
        if ($request->session()->has('role')) {
            $role = strtolower($request->session()->get('role'));
            $request->session()->forget('role');
            Auth::guard('web')->logout();
            return redirect('/');
        } else {
            Auth::guard('web')->logout();
            return redirect('/');
        }
    }
}
