<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campanha extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'status',
        'user_id',
        'total_contatos',
        'data_inicio',
        'data_fim',
        'opcao_mei',
        'estado_id',
        'cidade_id',
        'data_abertura_inicio',
        'data_abertura_fim',
        'produto',
        'mensagem',
    ];

    public function canais(): BelongsToMany
    {
        return $this->belongsToMany(Canal::class, 'canal_campanha');
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }

    public function codade(): BelongsTo
    {
        return $this->belongsTo(Cidade::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function prospects(): HasMany
    {
        // O Laravel assume que a chave estrangeira em 'prospects' é 'canal_id'
        return $this->hasMany(Prospect::class);
    }
}
