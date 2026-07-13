<?php

namespace App\Notifications;

use App\Models\Compra;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NuevoPedidoNotification extends Notification
{
    use Queueable;

    protected $compra;

    /**
     * Recibe el modelo de la compra/pedido recién creado
     */
    public function __construct(Compra $compra)
    {
        $this->compra = $compra;
    }

    /**
     * Definimos que esta notificación se enviará usando el canal de WebPush
     */
    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    /**
     * Construye el JSON exacto estructurado para tu sw.js
     */
    public function toWebPush($notifiable, $notification)
    {
        $numeroDoc = $this->compra->numero_comprobante ?? "N° {$this->compra->id}";

        // 🔄 Si el estado es 'recibida', personalizamos el push como una confirmación de ingreso
        if ($this->compra->estado === 'recibida') {
            $titulo = '✅ Pedido de Compra Confirmado';
            $cuerpo = "La compra {$numeroDoc} ha sido confirmada e ingresada al almacén. Total: S/ " . number_format($this->compra->total, 2);
        } else {
            // Si el estado es 'pendiente' (Flujo anterior)
            $titulo = '📦 Nuevo Pedido de Abastecimiento';
            $cuerpo = "Se ha creado el pedido {$numeroDoc} pendiente de aprobación por un total de S/ " . number_format($this->compra->total, 2);
        }

        return (new WebPushMessage)
            ->title($titulo)
            ->body($cuerpo)
            ->icon('/Sneat-Admin/assets/img/favicon/logo.ico')
            ->badge('/Sneat-Admin/assets/img/favicon/logo.ico')
            ->data([
                'url' => url('/compras')
            ]);
    }
}
