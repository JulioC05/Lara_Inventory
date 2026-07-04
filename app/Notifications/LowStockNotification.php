<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class LowStockNotification extends Notification
{
    use Queueable;

    protected $producto;

    // Recibimos el producto que se está agotando
    public function __construct($producto)
    {
        $this->producto = $producto;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('⚠️ ¡Alerta de Stock Mínimo!')
            ->icon('Sneat-Admin/assets/img/favicon/logo.ico')
            ->body("El producto '{$this->producto->nombre}' llegó a su límite. Quedan solo {$this->producto->stock} unidades (Mínimo permitido: {$this->producto->stock_minimo}).")
            ->badge('Sneat-Admin/assets/img/favicon/logo.ico')
            ->data(['url' => url('/productos')]);
    }
}
