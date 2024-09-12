<?php

namespace App\Http\Controllers;

use Google2FA;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Google2FAController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Show the setup page for Google Authenticator.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function setup()
    {
        Google2FA::setQRCodeBackend('svg');
        $secret = Google2FA::generateSecretKey();

        $qr = Google2FA::getQRCodeInline(
            config('app.name'),
            Auth::user()->email,
            $secret
        );

        return view('google2fa.setup', compact('secret', 'qr'));
    }

    /**
     * Store secret key for the authenticated user.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'secret' => 'required|string',
        ]);

        DB::beginTransaction();

        try {

            $user = User::find(Auth::id());
            $user->google2fa_secret = ($user->google2fa_secret ? NULL : $request->secret);
            $user->save();

            DB::commit();
            return redirect('/home')->with('status', 'Two-factor authentication has been ' . ($user->google2fa_secret ? 'enabled.' : 'disabled.'));

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->withErrors($e->getMessage());

        }
    }

    /**
     * Verify One Time Password for the authenticated user
     * 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function verify(Request $request)
    {

        $request->validate([
            'otp' => 'required|numeric',
        ]);

        if (!Google2FA::verifyKey(Auth::user()->google2fa_secret, $request->otp)) {
            return back()->withErrors('Invalid One Time Password');
        }

        Google2FA::login();

        return redirect('/home');
    }
}
