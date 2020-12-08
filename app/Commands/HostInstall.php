<?php

namespace App\Commands;

use App\Models\Host;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class HostInstall extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'host:install {--id=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Transfer default nginx configuration to server';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $host = Host::findOrFail($this->option('id'));

        $config = view('nginx.site', [
            'ssl' => false,
            'hostname' => $host->name,
            'root' => $host->root,
        ]);

        $host->server->transferStringAsFile($config, "/etc/nginx/sites-available/{$host->name}");

        if ($this->confirm('Transfer sample index.php in the document root?')) {
            $host->server->transferStringAsFile(<<<TXT
            <?php
            phpinfo();

            TXT, "{$host->root}/index.php");
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
