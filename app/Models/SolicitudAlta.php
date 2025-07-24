<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\DocumentacionAltas;
use App\Models\User;

class SolicitudAlta extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'solicitante',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'curp',
        'rfc',
        'nss',
        'estado_civil',
        'domicilio_calle',
        'domicilio_numero',
        'domicilio_colonia',
        'domicilio_ciudad',
        'domicilio_estado',
        'sd',
        'sdi',
        'telefono',
        'email',
        'estatura',
        'peso',
        'status',
        'observaciones',
        'rol',
        'punto',
        'created_at',
        'updated_at',
    ];

    public function documentacion()
    {
        return $this->hasOne(DocumentacionAltas::class, 'solicitud_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'sol_alta_id');
    }
    public function usuario() {
        return $this->hasOne(User::class, 'sol_alta_id');
    }

}
