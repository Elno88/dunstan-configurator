<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use KFoobar\Uuid\Traits\HasUuid;

class Lead extends Model
{
    use HasFactory;
    use HasUuid;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]|bool
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data'         => 'json',
        'animal_birth' => 'date',
        'started_at'   => 'datetime',
        'renewal_at'   => 'datetime',
        'exported_at'  => 'datetime',
    ];
}
