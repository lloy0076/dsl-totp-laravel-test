<?php

namespace App\Http\Controllers;

use App\Repositories\StorageRepositoryContract;
use Endroid\QrCode\QrCode;
use Illuminate\Http\request;
use Illuminate\Support\Str;
use OTPHP\Factory;
use OTPHP\TOTP;

class HomeController extends Controller
{
    /**
     * @var StorageRepositoryContract
     */
    protected $storage;

    /**
     * Create a new controller instance.
     *
     * @param StorageRepositoryContract $repository
     */
    public function __construct(StorageRepositoryContract $repository)
    {
        $this->middleware('auth');
        $this->storage = $repository;
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

        $this->storage->setToken($newToken);
        $this->storage->setProvisioningUri($provisioningUri);
        $this->storage->setSecret($totp->getSecret());

        return view('generate_token',
            [
                'newToken' => $newToken,
                'totp' => $totp->now(),
                'provisioning_uri' =>
                    Str::limit($provisioningUri, 256),
            ]);
    }

    /**
     * Generates the QR.
     *
     * @param request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function generateQr(Request $request)
    {
        if ($this->storage->getProvisioningUri()) {
            $provisioningUri = $this->storage->getProvisioningUri();
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
        if (!$this->storage->getProvisioningUri()) {
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

        if (!$this->storage->getSecret() || !$this->storage->getProvisioningUri()) {
            return redirect('welcome')->with('error', 'Generate a secret first.');
        }

        $provisioningUri = $this->storage->getProvisioningUri();

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
        $token = $this->storage->getToken() ?? '<No Token Set>';
        $secret = $this->storage->getSecret() ?? '<No Secret>';
        $provisioningUri = $this->storage->getProvisioningUri() ?? '<No Provisioning Uri>';

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
    public function clear(\Request $request)
    {
        $this->storage->forget();

        return redirect('welcome')->with('status', 'Token, secret and provisioning URI removed.');
    }

}
