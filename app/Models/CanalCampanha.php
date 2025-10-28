<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class CanalCampanha extends Pivot
{
    /**
     * Indica que este modelo é para uma tabela pivô.
     */
    protected $table = 'canal_campanha';

    /**
     * Desabilita o auto-incremento da chave primária.
     * Necessário porque temos uma chave primária composta (canal_id, campanha_id).
     */
    public $incrementing = false;

    /**
     * Desabilita os timestamps (created_at, updated_at).
     * Faça isso APENAS se você não os adicionou na migration.
     * Se você tiver timestamps, remova esta linha.
     */
    public $timestamps = false;

    // --- Bônus: Relações inversas ---
    // Isso permite que você acesse os pais a partir do modelo pivô.

    /**
     * Obtém o canal associado.
     */
    public function canal()
    {
        return $this->belongsTo(Canal::class, 'canal_id');
    }

    /**
     * Obtém a campanha associada.
     */
    public function campanha()
    {
        return $this->belongsTo(Campanha::class, 'campanha_id');
    }
}
