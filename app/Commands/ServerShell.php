<?php

namespace App\Commands;

use App\Models\Server;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ServerShell extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'server:shell {name}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Start an ssh shell';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $server = Server::where('name', $this->argument('name'))->first();

        // $this->info("Connecting to {$server->name} ({$server->id})");

        shell_exec("echo '{$server->private_key}' > /tmp/nod.server.{$server->id}.key && chmod 600 /tmp/nod.server.{$server->id}.key");

        // $this->info("Configured private key");

        if ($pid = pcntl_fork()) {
            pcntl_exec('/usr/bin/ssh', ["{$server->username}@{$server->ip}", "-i", "/tmp/nod.server.{$server->id}.key"]);
        }
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
