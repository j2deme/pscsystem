<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class MensajesChat extends Component
{
    public $conversation;
    public $messages = [];
    public $body = '';

    protected $listeners = ['conversacionSeleccionada' => 'cargarConversacion',

        'conversacionSeleccionada' => 'cargarConversacion',
        'cerrarConversacion' => 'cerrarConversacion',
    ];

    public function cargarConversacion($id)
    {
        if (is_null($id)) {
            $this->conversation = null;
            $this->messages = [];
            return;
        }

        $this->conversation = Conversation::with(['messages.user', 'users'])->find($id);

        if ($this->conversation) {
            $this->messages = $this->conversation->messages->toArray();
        } else {
            $this->messages = [];
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
}
