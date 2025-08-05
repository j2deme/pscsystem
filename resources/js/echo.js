import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT,
    forceTLS: import.meta.env.VITE_REVERB_SCHEME === 'https',
    enabledTransports: ['ws', 'wss'],
    auth: {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
        }
    }
});

window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('✅ Echo: Conexión WebSocket establecida');
    console.log('🔑 Socket ID:', window.Echo.socketId());
});

window.Echo.connector.pusher.connection.bind('error', (err) => {
    console.error('❌ Echo: Error de conexión', err);
});

window.Echo.connector.pusher.connection.bind('state_change', (states) => {
    console.log('🔁 Estado de conexión:', states);
});
