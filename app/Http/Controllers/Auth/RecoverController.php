<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AbstractController;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class RecoverController extends AbstractController
{
    /**
     * Display the password recovery form
     *
     * @return View
     */
    public function index(): View
    {
        return $this->view(
            'pages.auth.password.recover',
            __('Recover password'),
            __('Recover your account')
        );
    }

    /**
     * Post action to send the password recovery email
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function send(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'regex:/^[^@]+@[^@]+\.[a-z]{2,}$/'
            ],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return $this->redirect('password.sent', __($status));
        }
        return $this->redirect('password.index', __($status), 'danger');
    }

    /**
     * Display the password recovery email sent page
     *
     * @return View
     */
    public function sent(): View
    {
        return $this->view(
            'pages.auth.password.sent',
            __('Password recovery email sent'),
            __('Password recovery email sent')
        );
    }

    /**
     * Display the password reset form
     *
     * @param string $token
     * @return View
     */
    public function reset(string $token): View
    {
        return $this->view(
            'pages.auth.password.reset',
            __('Reset password'),
            __('Reset your account password'),
            [
                'token' => $token,
            ]
        );
    }

    /**
     * Post action to update the password
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'regex:/^[^@]+@[^@]+\.[a-z]{2,}$/'
            ],
            'password' => 'required|string|min:6|confirmed'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return $this->redirect('auth.signin', __($status));
        }
        return $this->redirect('password.reset', __($status), 'danger');
    }
}
