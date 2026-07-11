<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        if ($request->user()->role === 'super_admin') {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Email verified. Welcome to the Spaio control center.');
        }

        return redirect()->intended(route('panel.index', absolute: false))
            ->with('success', 'Email verified. Welcome to your Spaio dashboard.');
    }
}
