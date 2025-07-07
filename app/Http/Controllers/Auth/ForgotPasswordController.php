<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
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

    /**
     * Override the method to send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        // 1. Validasi input untuk NIP dan Email
        $request->validate([
            'nip'   => 'required|string|size:18',
            'email' => 'required|email',
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.size' => 'NIP harus terdiri dari 18 digit.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
        ]);

        // 2. Cari pengguna berdasarkan NIP dan Email untuk memastikan keduanya cocok
        $user = User::where('nip', $request->nip)
                    ->where('email', $request->email)
                    ->first();

        // 3. Jika pengguna tidak ditemukan, kembalikan dengan pesan error
        if (!$user) {
            return back()->withInput($request->only('nip', 'email'))
                         ->withErrors(['email' => 'NIP dan Alamat Email yang Anda masukkan tidak cocok.']);
        }

        // 4. Jika pengguna ditemukan, lanjutkan proses pengiriman link reset password bawaan Laravel
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        // 5. Kirim respons berdasarkan hasil dari broker
        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, $response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }
}