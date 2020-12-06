<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'ip',
        'username'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $hidden = [
        'private_key'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * Check ssh credentials
     */
    public function checkCredentials($username, $key): bool
    {
        sleep(1);
        return true;
    }

    /**
     * Check ssh credentials
     */
    public function checkCredentialsAndUpdate($username, $key): bool
    {
        if($this->checkCredentials($username, $key)){

            $this->forceFill([
                'username' => $username,
                'private_key' => $key,
            ]);

            $this->save();

            return true;
        }
    }
}
