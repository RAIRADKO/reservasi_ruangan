<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;

class ForgotPasswordController extends Controller
{


    use SendsPasswordResetEmails;

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'nip'   => 'required|string|size:18',
            'email' => 'required|email',
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.size' => 'NIP harus terdiri dari 18 digit.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
        ]);
        $user = User::where('nip', $request->nip)
                    ->where('email', $request->email)
                    ->first();
        if (!$user) {
            return back()->withInput($request->only('nip', 'email'))
                         ->withErrors(['email' => 'NIP dan Alamat Email yang Anda masukkan tidak cocok.']);
        }
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );
        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, $response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }
}