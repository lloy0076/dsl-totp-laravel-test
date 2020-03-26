<?php

namespace App\Http\Controllers;

use Illuminate\Http\request;
use Illuminate\Support\Str;
use OTPHP\TOTP;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Generate a new token.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function generateToken(Request $request)
    {
        $newToken = $request->user()->getApiToken();

        $totp = TOTP::create();
        $totp->setLabel($request->user()->email);

        $provisioningUri = $totp->getProvisioningUri();

        return view('generate_token', ['newToken' => $newToken, 'totp' => $totp->now(), 'provisioning_uri' =>
            Str::limit($provisioningUri, 256)]);
    }
}
