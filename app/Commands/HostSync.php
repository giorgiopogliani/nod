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
        $config = json_decode(file_get_contents(getcwd() . '/nod.config.json'));

        if (! Nod::validate($config)) {
            $this->error('Error: config not valid');
        }

        $host = Host::byName($config->host);

        if (! $host) {
            $this->error('Error: host not found');
        }

        $args = [
            "-r",
            "--delete",
            "--update",
            "--filter=':- .gitignore'",
            "-v",
            "-e",
            "\"ssh -i '{$host->server->getPrivateKeyPath()}'\"",
            "./",
            "{$host->server->username}@{$host->server->ip}:{$host->base}{$config->upload}/"
        ];

        if (-1 === ($pid = pcntl_fork())) {
            throw new \Exception('Unable to fork a new process.');
        }

        if (0 === $pid) {
            pcntl_exec('rsync', $args);
            exit;
        }

        pcntl_wait($status);

        $this->info('done: ' . $status);
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
