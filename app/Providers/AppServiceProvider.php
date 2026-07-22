<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        //Cambiamos a 'pinggy-free.link' para que coincida exactamente con tu túnel
        // if (str_contains(request()->getHost(), 'pinggy-free.link')) {
        //     \Illuminate\Support\Facades\URL::forceScheme('https');
        // }

        // 🚀 Interceptamos el correo para meterle el estilo de Sneat
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {

            // Reconstruimos la URL oficial a la que irá el usuario
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            // Obtenemos el tiempo de expiración configurado en auth.php (por defecto 60 min)
            $count = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');

            return (new MailMessage)
                ->subject('Recuperar Contraseña - Económica')
                ->view('emails.reset-password', [
                    'url' => $url,
                    'count' => $count,
                    'user' => $notifiable
                ]);
        });
    }
}
