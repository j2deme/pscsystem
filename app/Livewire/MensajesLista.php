<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class MensajesLista extends Component
{
    public $conversaciones;

    public function mount()
    {
        $this->conversaciones = Auth::user()
            ->conversations()
            ->with(['users.documentacionAltas', 'latestMessage'])
            ->get();
    }

    public function seleccionarConversacion($conversationId)
    {
        $this->dispatch('conversacionSeleccionada', id: $conversationId);
    }

    public function render()
    {
        return view('livewire.mensajes-lista');
    }
}

