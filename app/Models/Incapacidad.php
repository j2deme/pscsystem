<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
class Incapacidad extends Model
{
    use HasFactory;
  protected $table = 'incapacidades';
    protected $fillable = [
        'user_id',
        'motivo',
        'tipo_incapacidad',
        'ramo_seguro',
        'dias_incapacidad',
        'fecha_inicio',
        'folio',
        'ruta_archivo_pdf',
    ];


    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
