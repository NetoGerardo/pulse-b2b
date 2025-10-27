<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pais extends Model
{

    use SoftDeletes;

    protected $table = 'paises';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'status',
    ];


    public function estados(): HasMany
    {
        return $this->hasMany(Estado::class);
    }

    public function cities(): HasManyThrough
    {
        return $this->hasManyThrough(Cidade::class, Estado::class);
    }
}
