<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prospect extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'campanha_id',
        'canal',
        'telefone',
        'dados',
        'status_ligacao',
        'status_whatsapp',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dados' => 'array', // Isso garante que o campo 'dados' seja salvo como JSON
    ];

    /**
     * Define o relacionamento com a Campanha.
     */
    public function campanha(): BelongsTo
    {
        return $this->belongsTo(Campanha::class, 'campanha_id');
    }
}