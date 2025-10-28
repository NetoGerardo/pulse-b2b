<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Canal extends Model
{
    use SoftDeletes;

    protected $table = 'canais';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
    ];

    public function campanhas(): BelongsToMany
    {
        return $this->belongsToMany(Campanha::class, 'canal_campanha');
        // 1º argumento: O Model relacionado
        // 2º argumento: O nome da tabela pivô (opcional se você seguiu a convenção)
    }

    public function prospects(): HasMany
    {
        // O Laravel assume que a chave estrangeira em 'prospects' é 'canal_id'
        return $this->hasMany(Prospect::class);
    }
}
