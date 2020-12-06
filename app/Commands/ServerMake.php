<?php

namespace App\Commands;

use App\Models\Server;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ServerMake extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'server:make
                            {--name=} Friendly name for the server (required)
                            {--ip=} The ip address for the server (required)
                            ';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a server';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Server::create([
            'name' => $this->option('name'),
            'ip' => $this->option('ip'),
        ]);

        $this->info('Server created successfully!');
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
