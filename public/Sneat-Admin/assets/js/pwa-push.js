// Asegúrate de reemplazar esto con tu CLAVE PÚBLICA VAPID exacta que generaste en el .env
const VAPID_PUBLIC_KEY = 'BAcRoG4Cw_ydVGz9yCvA2ghg2AYDcBJg7ePFt62_dpUXYbY1Jm6EZLtUV9io4694i3AyO6X3PiuJJ_jacr9cxC4';

document.addEventListener('DOMContentLoaded', () => {
  // Verificar si el navegador es compatible con Service Workers y Push
  if ('serviceWorker' in navigator && 'PushManager' in window) {
    console.log('El navegador soporta Notificaciones Push.');
    inicializarSuscripcionPush();
  } else {
    console.warn('Las notificaciones Push no son soportadas por este navegador o celular.');
  }
});

function inicializarSuscripcionPush() {
  navigator.serviceWorker.ready.then(registration => {
    // Verificar si el usuario ya tiene una suscripción activa
    registration.pushManager.getSubscription().then(subscription => {
      const estaSuscrito = !(subscription === null);

      if (estaSuscrito) {
        console.log('El usuario ya está suscrito a las notificaciones.');
        // Enviar la suscripción al servidor por si acaso cambió de token
        enviarSuscripcionAlServidor(subscription);
      } else {
        console.log('El usuario NO está suscrito. Solicitando permisos...');
        // Aquí podrías amarrar esto a un botón en tu diseño de Sneat o lanzarlo directo
        solicitarPermisoPush(registration);
      }
    });
  });
}

function solicitarPermisoPush(registration) {
  const opciones = {
    userVisibleOnly: true,
    // Convertimos la llave VAPID de texto a un Array de bytes para que el navegador la entienda
    applicationServerKey: urlB64ToUint8Array(VAPID_PUBLIC_KEY)
  };

  registration.pushManager
    .subscribe(opciones)
    .then(subscription => {
      console.log('¡Permiso concedido! Dispositivo registrado en Google/Apple.');
      enviarSuscripcionAlServidor(subscription);
    })
    .catch(error => {
      if (Notification.permission === 'denied') {
        console.warn('El usuario bloqueó el permiso de notificaciones.');
      } else {
        console.error('Error al suscribir el dispositivo:', error);
      }
    });
}

function enviarSuscripcionAlServidor(subscription) {
  // Extraer el token de CSRF de Laravel para poder pasar la seguridad de la API
  const tokenCsrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  fetch('/push-subscription/update', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': tokenCsrf,
      Accept: 'application/json'
    },
    body: JSON.stringify(subscription)
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        console.log('Suscripción guardada con éxito en la base de datos de Laravel.');
      }
    })
    .catch(error => console.error('Error al enviar la suscripción a Laravel:', error));
}

// Función auxiliar obligatoria para codificar la llave VAPID correctamente
function urlB64ToUint8Array(base64String) {
  const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
  const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
  const rawData = window.atob(base64);
  const outputArray = new Uint8Array(rawData.length);
  for (let i = 0; i < rawData.length; ++i) {
    outputArray[i] = rawData.charCodeAt(i);
  }
  return outputArray;
}
