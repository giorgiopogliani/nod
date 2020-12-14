<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ConfigInit extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'config:init {--host=} {--upload=} {--force}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create an empty configuration file: nod.config.json';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $config = [];

        if ($this->option('host')) {
            $config['host'] = $this->option('host');
        }

        if ($this->option('upload')) {
            $config['upload'] = $this->option('upload');
        }

        if (! $this->option('force')) {
            if (file_exists(getcwd() . '/nod.config.json')) {
                $this->getOutput()->writeln('🚫 <options=bold><fg=#f00;bold>nod.config.json already exists.</></>');

                return;
            }
        }

        file_put_contents(getcwd() . '/nod.config.json', json_encode($config));

        $this->info('✅ Created Nod config file: nod.config.json');
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
