<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    /**
     * Enviar enlace de restablecimiento de contraseña al usuario
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
        ]);

        // Primero, buscar el usuario por username para obtener su email
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'username' => ['No se encontró un usuario con ese nombre de usuario.'],
            ]);
        }

        // Enviar email de restablecimiento usando el email del usuario encontrado
        $status = Password::sendResetLink(['email' => $user->email]);

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Se ha enviado un enlace de restablecimiento de contraseña a tu correo electrónico.']);
        }

        throw ValidationException::withMessages([
            'username' => [__($status)],
        ]);
    }

    /**
     * Restablecer la contraseña del usuario
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'username' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Buscar el usuario por username para obtener su email
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'username' => ['No se encontró un usuario con ese nombre de usuario.'],
            ]);
        }

        // Realizar el restablecimiento de contraseña usando el email
        $status = Password::reset(
            array_merge($request->only('password', 'password_confirmation', 'token'), ['email' => $user->email]),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Tu contraseña ha sido restablecida.']);
        }

        throw ValidationException::withMessages([
            'username' => [__($status)],
        ]);
    }
} 