<?php

namespace App\Commands;

use App\Models\Host;
use App\Models\Server;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Performing\Wait\Spinner;

class HostImport extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'host:import {--server=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Import nginx hosts from server';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->option('server');

        /** @var Server */
        $server = Server::findOrFail($id);

        $spinner = Spinner::create();

        $spinner->update('Fetching existing configurations...');

        $spinner->start();

        $contents = $server->exec("'cd /etc/nginx/sites-enabled && ls /etc/nginx/sites-enabled | xargs cat'");
        $contents = str_replace("\n", ' ', $contents);
        $re = '/(?:server_name\s+(.*?)\;).*?(?:root\s+(.*?)\;)/m';
        preg_match_all($re, $contents, $matches, PREG_SET_ORDER, 0);
        $data = collect($matches)->map(fn ($match) => ['name' => $match[1], 'root' => $match[2]])->unique('name');

        $spinner->stop();

        foreach($data as ['name' => $name, 'root' => $root]) {
            if (Host::where(['name' => $name])->count() == 0) {
                $host = $server->hosts()->create([
                    'name' => $name,
                    'root' => $root
                ]);
                $this->info("$host->name imported");
            }
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
