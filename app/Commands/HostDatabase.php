<?php

namespace App\Commands;

use App\Models\Host;
use App\Nod;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class HostDatabase extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'host:database
                            {name} Database name
                            {--host=} Host id to select
                            {--user=root} Database user
                            {--pass=} Database pass
                           ';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create new database on remote server';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $config = Nod::getConfig();

        $name = $this->argument('name');

        if (Nod::validate($config)) {
            $host = Host::byName($config->host);
        } else {
            $host = Host::byName($this->option('host'));
        }

        $user = $this->option('user');
        $pass = $this->option('pass');

        spinner()->update('Creating remote database');
        spinner()->start();
        $host->server->exec("\"mysql -u{$user} -p{$pass} -e 'create database $name'\"");
        spinner()->stop();
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
