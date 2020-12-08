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

        $this->info('Transfering nginx configuration');

        $host->server->transferStringAsFile($config, "/etc/nginx/sites-available/{$host->name}.conf");

        $this->info('Creating document root');

        $host->server->exec("mkdir -p {$host->root}");

        if ($this->confirm('Transfer sample index.php in the document root?')) {
            $host->server->transferStringAsFile(<<<TXT
            <?php
            phpinfo();

            TXT, "{$host->root}/index.php");

            $this->info('Sample file created');
        }
        $this->info('Activaiting host file');

        $host->server->exec("ln -sf /etc/nginx/sites-available/{$host->name}.conf /etc/nginx/sites-enabled/{$host->name}.conf");

        $this->info('done!');
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
