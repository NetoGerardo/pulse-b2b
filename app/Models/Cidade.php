<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cidade extends Model
{

    use SoftDeletes;

    protected $table = 'cidades';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'nome_abreviado',
        'status',
        'estado_id',
        'iso',
        'iso_ddd',
        'status',
        'slug',
        'populacao',
        'lat',
        'long',
        'renda_per_capita',
        'created_at',
        'updated_at'
    ];

    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }
}
