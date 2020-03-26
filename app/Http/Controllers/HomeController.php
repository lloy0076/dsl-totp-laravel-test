<?php

namespace App\Http\Controllers;

use Endroid\QrCode\QrCode;

use Illuminate\Http\request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

use OTPHP\TOTP;
use OTPHP\Factory;

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
        $newToken = $request->user()->getApiToken();

        $totp = TOTP::create();
        $totp->setLabel($request->user()->email);

        $provisioningUri = $totp->getProvisioningUri();

        Session::put('provisioning-uri', $provisioningUri);
        Session::put('secret', $totp->getSecret());

        return view('generate_token', ['newToken' => $newToken, 'totp' => $totp->now(), 'provisioning_uri' =>
            Str::limit($provisioningUri, 256)]);
    }

    public function generateQr(Request $request)
    {
        if (Session::get('provisioning-uri')) {
            Log::debug('Using session provisioning URI.');
            $provisioningUri = Session::get('provisioning-uri');
        } else {
            return redirect('welcome')->with('error', 'Generate a secret first.');
        }

        Log::info("Trying to encode $provisioningUri with secret " . Session::get('secret') . ".");
        $qrCode = new QrCode($provisioningUri);

        return response($qrCode->writeString())->withHeaders(['Content-Type' => $qrCode->getContentType()]);
    }

    /**
     * Show the verification screen.
     *
     * @param request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function verify(Request $request) {
        return view('verify');
    }

    /**
     * Perform the verification.
     *
     * @param request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function performVerify(Request $request) {
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
}
