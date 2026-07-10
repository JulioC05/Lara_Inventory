<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Throwable;

class ForgotPasswordController extends Controller
{
    // Muestra el formulario para ingresar el correo
    public function showLinkRequestForm()
    {
        return view('modules.auth.forgot-password', ['titulo' => 'Recuperar Contraseña']);
    }

    // Procesa el envío del enlace de recuperación (irá al log)
    public function sendResetLinkEmail(Request $request)
    {

        try {
            $request->validate(['email' => 'required|email|exists:users,email']);

            $status = Password::sendResetLink($request->only('email'));

            if ($status === Password::RESET_LINK_SENT) {
                return back()->with('status', '¡Enlace de recuperación generado! Solicítalo al administrador o búscalo en los logs.');
            }

            // return back()->withErrors(['email' => 'No se pudo enviar el enlace.']);
        } catch (TransportExceptionInterface $e) {
            // Captura errores específicos del envío/red de Mailer
            return back()->withErrors(['email' => 'Error de Brevo: ' . $e->getMessage()]);
        } catch (Throwable $e) {
            // Captura cualquier otro error de PHP o Laravel
            return back()->withErrors(['email' => 'Error general: ' . $e->getMessage()]);
        }
    }

    // Muestra la vista para poner la nueva contraseña
    public function showResetForm(Request $request, $token = null)
    {
        return view('modules.auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
            'titulo' => 'Restablecer Contraseña'
        ]);
    }

    // Guarda la nueva contraseña en la BD
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect('/')->with('status', 'Contraseña restablecida con éxito. Ya puedes iniciar sesión.');
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
