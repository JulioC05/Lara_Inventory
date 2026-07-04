<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PushSubscriptionController extends Controller
{
    /**
     * Guarda o actualiza la suscripción Push del dispositivo del usuario.
     */
    public function update(Request $request)
    {
        // Validar que nos envíen el endpoint único del navegador
        $request->validate([
            'endpoint' => 'required|url',
            'keys.auth' => 'required',
            'keys.p256dh' => 'required',
        ]);

        $user = Auth::user();

        // El paquete de WebPush nos da el método updatePushSubscription() de forma nativa
        $user->updatePushSubscription(
            $request->endpoint,
            $request->keys['p256dh'],
            $request->keys['auth']
        );

        return response()->json([
            'success' => true,
            'message' => 'Dispositivo suscrito con éxito a las notificaciones.'
        ]);
    }

    /**
     * Elimina la suscripción (Por si el usuario decide desactivarlas desde el panel).
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|url',
        ]);

        $user = Auth::user();
        
        // Eliminar la suscripción de la base de datos
        $user->deletePushSubscription($request->endpoint);

        return response()->json([
            'success' => true,
            'message' => 'Suscripción eliminada correctamente.'
        ]);
    }
}