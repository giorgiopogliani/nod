<?php

namespace App\Commands;

use App\Models\Host;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Performing\Wait\Spinner;

class HostSecure extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'host:secure {id}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a certificate with letsencrypt';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $host = Host::findOrFail($this->argument('id'));

        $this->info('Connecting to server');

        $host->server->exec("certbot certonly -d {$host->name} --nginx");
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
