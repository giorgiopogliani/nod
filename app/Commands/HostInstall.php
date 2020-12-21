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
    protected $signature = 'host:install {id} {--ssl} {--sslpath=}';

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
        /** @var Host $host */
        $host = Host::findOrFail($this->argument('id'));

        $config = view('nginx.site', [
            'ssl' => $this->option('ssl'),
            'hostname' => $host->name,
            'base' => $host->base,
            'root' => $host->root,
            'sslpath' => $this->option('sslpath')
        ]);

        wait('Transfering configuration...', function () use ($host, $config) {
            $host->server->transferStringAsFile($config, "/etc/nginx/sites-available/{$host->name}.conf");
        });

        wait('Creating directories...', function () use ($host) {
            $host->server->exec("mkdir -p {{$host->base},{$host->base}/logs,{$host->root}}");
        });

        if ($this->confirm('Transfer sample index.php in the document root?')) {
            wait('Transfering sample...', function () use ($host) {
                $host->server->transferStringAsFile(<<<TXT
                <?php
                phpinfo();

                TXT, "{$host->root}/index.php");
            });
        }

        wait('Preparing hosts and permissions...', function () use ($host) {
            $script = $host->server->prepareSsh();

            $script->add("ln -sf /etc/nginx/sites-available/{$host->name}.conf /etc/nginx/sites-enabled/{$host->name}.conf");

            $script->add("chown www-data:www-data -R {$host->base}");

            $script->add("find {$host->base} -type f -exec chmod 644 {} \;");

            $script->add("find {$host->base} -type d -exec chmod 755 {} \;");

            $script->execute();
        });

        if ($this->confirm('Check configuration and relaod nginx?')) {
            wait('Reloading...', function () use ($host) {
                $host->server->exec("'nginx -t && /bin/systemctl reload nginx'");
            });
        }

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
