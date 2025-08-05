import './bootstrap';
import './echo';

// Opcional: Si necesitas acceder globalmente
window.LivewireEcho = {
    setup: (conversationId, componentId) => {
        if (window.setupChatListeners) {
            window.setupChatListeners(conversationId, componentId);
        }
    }
};

/*import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();*/
