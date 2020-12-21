<?php

namespace App\Commands;

use App\Models\Host;
use App\Models\Server;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use LaravelZero\Framework\Commands\Command;

class HostList extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'host:list
                            {--server=} The server to connect to
                            ';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'List enabled nginx host on a server';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $hosts = Host::select('id as hostid','name as hostname', 'base', 'root', 'server_id')->with('server:id,name,ip')->get();

        $hosts = $hosts->map(function($host) {
            return collect($host->toArray())
                ->merge($host->server->toArray())
                ->only(['hostid', 'hostname', 'base', 'root', 'name', 'ip']);
        });

        foreach($hosts as $host) {
            $this->output->writeln('----------');
            $this->output->writeln('Host: <info>' . $host['hostname'] . '</info> (' . $host['hostid']  .') ');
            $this->output->writeln('Server: ' . "<info>{$host['name']}</info>" . ' on ' . "<info>{$host['ip']}</info>");
            $this->output->writeln('Base: ' . $host['base']);
            $this->output->writeln('Root: ' . $host['root']);
        }
        $this->output->writeln('----------');

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
