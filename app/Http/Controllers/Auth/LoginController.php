<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
        session(['role' => $role]);
        $requestData = $request->except(['_token']);
        if (strpos($request->username, ":")) {
            list($u, $p) = explode(":", $request->username);
            $request->request->set('username', $u);
            $request->request->add(['password' => $p]);
            $requestData = $request->except(['_token']);
        }

        $this->validateLogin($request);
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        if (Auth::attempt($requestData))
        {
            return redirect(Auth::user()->redirect());
        }
        else
        {
            $this->incrementLoginAttempts($request);
            return back()->withErrors(['password' => "Datos incorrectos o no encontrados"])->withInput();
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
        if ($request->session()->has('role')) {
            $role = $request->session()->get('role');
            $request->session()->forget('role');

            Auth::guard('web')->logout();
            return redirect('login/' . $role);
        }
    }
}
