<?php

namespace App\Commands;

use App\Models\Host;
use App\Models\Server;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class HostRedirect extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'host:redirect
                            {--from=*} The server name for this host (required)
                            {--to=} The hostname (required)
                            {--ssl} Secure the configuration
                            ';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a new host';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var Host $host */
        $to = Host::byName($this->option('to'));

        /** @var Host[] $host */
        $hostnames = $this->option('from');

        foreach ($hostnames as $hostname) {
            $config = view('nginx.redirect', [
                'ssl' => $this->option('ssl'),
                'hostname' => explode(',', $hostname),
                'redirect' => $to->name,
            ]);

            echo $config;
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
