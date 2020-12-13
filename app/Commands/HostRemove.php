<?php

namespace App\Commands;

use App\Models\Host;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class HostRemove extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'host:remove {id}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Remove host by id';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $host = Host::findOrFail($this->argument('id'));

        if ($this->confirm("Are you sure? This will delete host $host->name")) {
            $host->delete();
            $this->info('Host deleted');
        } else {
            $this->warn('Host not deleted');
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
