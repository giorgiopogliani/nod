<?php

namespace App\Models;

use App\Support\Script;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\File;

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
     * Hosts relation.
     *
     * @return HasMany
     */
    public function hosts()
    {
        return $this->hasMany(Host::class);
    }

    /**
     * Get server by name.
     *
     * @param mixed $query
     * @param mixed $name
     * @return void
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name)->first();
    }

    /**
     * Create the temporary file of the private key.
     *
     * @return void
     */
    public function checkOrConfigurePrivateKey()
    {
        File::isWritable($this->getPrivateKeyPath());

        shell_exec("echo '{$this->private_key}' > {$this->getPrivateKeyPath()}");

        File::chmod($this->getPrivateKeyPath(), 0600);
    }

    /**
     * Return the temporary path of the private key.
     *
     * @return string
     */
    public function getPrivateKeyPath()
    {
        return "/tmp/nod.server.{$this->id}.key";
    }

    /**
     * Pass commands the you want exec on the server through ssh.
     *
     * @param mixed $cmd
     * @return string|null
     */
    public function exec($cmd)
    {
        return shell_exec("ssh {$this->username}@{$this->ip} -i {$this->getPrivateKeyPath()} " . $cmd);
    }

    /**
     * Return object to append all commands and
     * send all in one go.
     *
     * @return App\Support\Script
     */
    public function prepareSsh()
    {
        return new Script($this);
    }

    /**
     * Check ssh credentials
     */
    public function checkCredentials($username, $key): bool
    {
        /** TODO: */
        return true;
    }

    /**
     * Check ssh credentials and update server details
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

    /**
     *
     * @return void
     */
    public function transferStringAsFile($contents, $path)
    {
        $temp = saveStringAsTempFile($contents);

        $this->checkOrConfigurePrivateKey();

        shell_exec("scp -i {$this->getPrivateKeyPath()} $temp {$this->username}@{$this->ip}:$path");
    }
}
