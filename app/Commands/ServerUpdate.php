<?php

namespace App\Commands;

use App\Models\Server;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ServerUpdate extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'server:update
                            {name}
                            {--username=}
                            {--ip=}
                            ';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Update server details';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $server = Server::where('name', $this->argument('name'))->first();

        if ($this->option('username')) {
            $server->update([ 'username' => $this->option('username') ]);
        }

        if ($this->option('ip')) {
            $server->update([ 'ip' => $this->option('ip') ]);
        }

        $this->info('Server updated!');
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
