<?php

namespace App\Livewire;

use Livewire\Component;
use App\Events\MensajeEnviado;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class MensajesChat extends Component
{
    public $conversation;
    public $messages = [];
    public $body = '';
    public $componentId;
    public $conversationId;


    protected $listeners = [
        'conversacionSeleccionada' => 'cargarConversacion',
        'cerrarConversacion' => 'cerrarConversacion',
        //'mensajeRecibido' => 'agregarMensaje'
    ];
/*public function getListeners()
{
    $listeners = $this->listeners;

    if ($this->conversationId) {
        $channel = "private-conversacion.{$this->conversationId}";
        $fullEvent = "echo:{$channel},.MensajeEnviado";

        \Log::info('Suscripción a canal WebSocket:', [
            'channel' => $channel,
            'full_event' => $fullEvent,
            'conversation_id' => $this->conversationId,
        ]);

        $listeners[$fullEvent] = 'agregarMensaje';
    } else {
        \Log::warning('No se suscribe a canal WebSocket, conversationId no definido');
    }

    return $listeners;
}*/

public function updatedConversationId($value)
{
    \Log::info('conversationId actualizado:', ['conversationId' => $value]);
}
    public function mount()
    {
        $this->componentId = 'chat-' . uniqid();
    }

    public function agregarMensaje($data)
{
    \Log::info('Evento mensajeRecibido recibido en MensajesChat:', [
        'data' => $data,
        'conversation_id' => $this->conversation ? $this->conversation->id : null,
        'component_id' => $this->getId(),
        'messages_count_before' => count($this->messages)
    ]);
    if ($this->conversation && $this->conversation->id == $data['conversation_id']) {
        \Log::info('Añadiendo mensaje a la conversación:', [
            'message' => $data['message'],
            'conversation_id' => $this->conversation->id
        ]);
        $this->messages[] = $data['message'];
        \Log::info('Mensaje añadido, nuevo conteo:', [
            'messages_count_after' => count($this->messages)
        ]);
        $this->dispatch('scrollToBottom');
    } else {
        \Log::warning('Evento ignorado, conversación no coincide:', [
            'conversation_id' => $this->conversation ? $this->conversation->id : null,
            'received_conversation_id' => $data['conversation_id']
        ]);
    }
}

    public function cargarConversacion($id)
{
    \Log::info('Cargando conversación:', ['conversation_id' => $id]);
    if (is_null($id)) {
        $this->conversation = null;
        $this->messages = [];
        $this->conversationId = null;
        \Log::warning('Conversación ID nula');
        return;
    }

    $this->conversationId = $id;
    $this->dispatch('updatedConversationId', $id);
    $this->conversation = Conversation::with(['messages.user', 'users'])->find($id);

    if ($this->conversation) {
        $this->messages = $this->conversation->messages->toArray();
        \Log::info('Conversación cargada:', ['conversation_id' => $id]);
        $this->dispatch('refreshComponent'); // Forzar actualización del componente
    } else {
        $this->messages = [];
        $this->conversationId = null;
        \Log::warning('Conversación no encontrada:', ['conversation_id' => $id]);
    }
}

    public function enviarMensaje()
{
    $this->validate(['body' => 'required|string']);

    $msg = $this->conversation->messages()->create([
        'user_id' => Auth::id(),
        'body' => $this->body,
    ]);

    $msg->load('user');
    $this->messages[] = $msg->toArray();
    $this->body = '';

    \Log::info('Mensaje enviado:', [
        'message_id' => $msg->id,
        'conversation_id' => $this->conversation->id,
        'user_id' => Auth::id()
    ]);

    try {
        broadcast(new MensajeEnviado($msg))->toOthers();
        \Log::info('Evento MensajeEnviado emitido exitosamente:', [
            'message_id' => $msg->id,
            'conversation_id' => $this->conversation->id
        ]);
    } catch (\Exception $e) {
        \Log::error('Error al emitir MensajeEnviado:', [
            'message_id' => $msg->id,
            'conversation_id' => $this->conversation->id,
            'error' => $e->getMessage()
        ]);
    }

    $this->dispatch('scrollToBottom');
}

    public function cerrarConversacion()
    {
        $this->conversation = null;
        $this->messages = [];
    }

    public function render()
    {
        return view('livewire.mensajes-chat');
    }
    /*#[On('mensajeRecibido')]
    public function mensajeRecibido($data)
    {
        $this->messages[] = $data['message'];
        logger('🟢 Evento recibido en Livewire:', $data);
    }*/
}
