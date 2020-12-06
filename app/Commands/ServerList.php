<?php

namespace App\Commands;

use App\Models\Server;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ServerList extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'server:list';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'List all available servers';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->table(['ID','Name', 'IP', 'Username', 'Keys', 'Created', 'Updated'], Server::all());
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
