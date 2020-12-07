<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Host extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'root',
        'server_id'
    ];

    /**
     * Server relation.
     */
    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }
}
