<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estado extends Model
{

    use SoftDeletes;

    protected $table = 'estados';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'status',
        'sigla',
        'iso',
        'slug',
        'populacao',
        'pais_id'
    ];


    public function cidades(): HasMany
    {
        return $this->hasMany(Cidade::class);
    }

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class);
    }
}
