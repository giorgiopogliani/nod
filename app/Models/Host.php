<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Server $server
 *
 * @package App\Models
 */
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
        'base',
        'server_id'
    ];

    /**
     * Server relation.
     */
    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * Get host by name.
     *
     * @param mixed $query
     * @param mixed $name
     * @return void
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name)->first();
    }
}
