<?php

namespace App\Commands;

use App\Models\Host;
use App\Nod;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class HostSync extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'host:sync';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $config = Nod::getConfig();

        if (! Nod::validate($config)) {
            $this->error('Error: config not valid');
        }

        $host = Host::byName($config->host);

        if (! $host) {
            $this->error('Error: host not found');
        }

        $host->server->checkOrConfigurePrivateKey();

        $upload = str($host->base . '/' . $config->upload . '/')->replace('//', '/');

        $args = [
            "-rz",
            "-v",
            "--chown=www-data:www-data",
            "-e", "ssh -i '{$host->server->getPrivateKeyPath()}'",
            "--filter", ':- .gitignore',
            "--delete",
            "--update",
            "./",
            "{$host->server->username}@{$host->server->ip}:$upload"
        ];

        if (-1 === ($pid = pcntl_fork())) {
            throw new \Exception('Unable to fork a new process.');
        }

        if (0 === $pid) {
            pcntl_exec('/usr/local/bin/rsync', $args);
            exit;
        }

        pcntl_wait($status);

        $this->notify('Sync Completed', $host->hostname);

        echo PHP_EOL;

        $this->info('rsync exited with status: ' . $status);
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
