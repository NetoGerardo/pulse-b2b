<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserOrigem extends Model
{
    protected $table = 'user_origens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'origem_id',
    ];
}
