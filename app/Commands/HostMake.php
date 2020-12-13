<?php

namespace App\Commands;

use App\Models\Host;
use App\Models\Server;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class HostMake extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'host:make
                            {--server=} The server name for this host (required)
                            {--name=} The hostname (required)
                            {--base=} The base path for this hosts (required)
                            {--root=} The document root used by the web server can be relative to the base (required)
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
        $host = Server::byName($this->option('server'))->hosts()->create([
            'name' => $this->option('name'),
            'base' => $this->option('base'),
            'root' => $this->option('root'),
        ]);

        if ($host) {
            $this->info('Cretead ' . $host->name);
        } else {
            $this->error('Error');
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
