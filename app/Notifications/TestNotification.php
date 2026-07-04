<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
// Importamos el canal oficial de WebPush
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class TestNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        // Puedes pasarle datos aquí (como el stock de un licor o una venta)
    }

    // Decimos que esta notificación se enviará a través del canal WebPush
    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    // Estructuramos el diseño visual que tendrá la alerta en el celular
    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('¡Licorería Económica!')
            ->icon('Sneat-Admin/assets/img/favicon/logo.ico')
            ->body('Tu sistema de notificaciones en tiempo real está funcionando al 100%.')
            ->badge('Sneat-Admin/assets/img/favicon/logo.ico')
            ->data(['url' => url('/productos')]); 
    }
}
