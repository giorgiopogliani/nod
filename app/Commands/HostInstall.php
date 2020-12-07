<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class HostInstall extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'host:install';

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
        echo view('nginx.site', [
            'ssl' => false,
            'hostname' => 'test.com',
            'root' => '/home/projects/'
        ]);
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
