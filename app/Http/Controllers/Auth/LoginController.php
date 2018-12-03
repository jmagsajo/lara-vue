<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Libraries\PassportAPI;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request)
    {
        $field = filter_var($request->get($this->username()), FILTER_VALIDATE_EMAIL)
            ? $this->username()
            : 'username';

        return [
            $field => $request->get($this->username()),
            'password' => $request->password,
        ];
    }

    protected function authenticated(Request $request, $user)
    {   
        //put your thing in here
        $tokenRequest = New PassportAPI;

        $response = $tokenRequest->post( url('oauth/token'),  [
                'grant_type' => 'password',
                'client_id' => '2',
                'client_secret' => 'TU40PNtPUfiNFIg6BgeuQPegBp1HAN5qqphgAzLQ',
                'username' => $user->email,
                'password' => $request->password,
                'scope' => ''
        ]);

        dd($response);

       return redirect()->intended($this->redirectPath());
    }
}
