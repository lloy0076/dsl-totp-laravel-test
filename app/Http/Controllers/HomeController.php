<?php

namespace App\Http\Controllers;

use Endroid\QrCode\QrCode;
use Illuminate\Http\request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use OTPHP\Factory;
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
        return view('welcome');
    }

    /**
     * Generate a new token.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function generateToken(Request $request)
    {
        $keepOthers = $request->input('keep_others') ? true : false;
        $newToken = $request->user()->getApiToken($keepOthers);

        $totp = TOTP::create();
        $totp->setLabel($request->user()->email);

        $provisioningUri = $totp->getProvisioningUri();

        Session::put('token', $newToken);
        Session::put('provisioning-uri', $provisioningUri);
        Session::put('secret', $totp->getSecret());

        return view('generate_token',
            ['newToken' => $newToken,
                'totp' => $totp->now(),
                'provisioning_uri' =>
                    Str::limit($provisioningUri, 256)]);
    }

    /**
     * Generates the QR.
     *
     * @param request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function generateQr(Request $request)
    {
        if (Session::get('provisioning-uri')) {
            $provisioningUri = Session::get('provisioning-uri');
        } else {
            return redirect('welcome')->with('error', 'Generate a secret first.');
        }

        $qrCode = new QrCode($provisioningUri);

        return response($qrCode->writeString())->withHeaders(['Content-Type' => $qrCode->getContentType()]);
    }

    /**
     * Show the verification screen.
     *
     * @param request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function verify(Request $request)
    {
        if (!Session::get('provisioning-uri')) {
            return redirect('welcome')->with('error', 'Generate a secret first.');
        }

        return view('verify');
    }

    /**
     * Perform the verification.
     *
     * @param request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function performVerify(Request $request)
    {
        $verification = $request->input('verification');

        if (!$verification) {
            return redirect('verify')->with('error', 'No one time password was given.');
        }

        if (!Session::get('secret') || !Session::get('provisioning-uri')) {
            return redirect('welcome')->with('error', 'Generate a secret first.');
        }

        $provisioningUri = Session::get('provisioning-uri');

        $otp = Factory::loadFromProvisioningUri($provisioningUri);

        $verified = $otp->verify($verification);

        if ($verified) {
            return redirect('home')->with('status', "Code '$verification' successfully verified.");
        } else {
            return redirect('verify')->with('error', "Code '$verification' is invalid.");
        }
    }

    /**
     * Get info.
     *
     * @param request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function info(Request $request)
    {
        $label = $request->user()->email;
        $token = Session::get('token') ?? '<No Token Set>';
        $secret = Session::get('secret') ?? '<No Secret>';
        $provisioningUri = Session::get('provisioning-uri') ?? '<No Provisioning Uri>';

        return view('info',
            [
                'label' => $label,
                'secret' => $secret,
                'provisioning_uri' => $provisioningUri,
                'token' => $token,
            ]
        );
    }

    /**
     * Clear the session.
     *
     * @param \Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function clear(\Request $request) {
        $remove = ['token', 'secret', 'provisioning-uri'];

        foreach($remove as $key) {
            Session::remove($key);
        }

        return redirect('welcome')->with('status', 'Token, secret and provisioning URI removed.');
    }

}
