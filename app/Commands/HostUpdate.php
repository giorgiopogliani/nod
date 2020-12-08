<?php

namespace App\Commands;

use App\Models\Host;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class HostUpdate extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'host:update
                            {id} The ID of the host to update
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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $host = Host::findOrFail($this->argument('id'));

        if ($this->option('server')) {
            $host->update(['server' => $this->option('server') ]);
        }

        if ($this->option('name')) {
            $host->update(['name' => $this->option('name') ]);
        }

        if ($this->option('base')) {
            $host->update(['base' => $this->option('base') ]);
        }

        if ($this->option('root')) {
            $host->update(['root' => $this->option('root') ]);
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
