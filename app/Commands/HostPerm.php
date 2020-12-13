<?php

namespace App\Commands;

use App\Models\Host;
use App\Nod;
use App\Support\Script;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class HostPerm extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'host:perm
                            {--owner=www-data}
                            {--group=www-data}
                            {--file=644}
                            {--directory=755}
                            ';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Reset remote permissions in the base directory of the host';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $config = Nod::getConfig();

        $host = Host::byName($config->host);

        $script = new Script($host->server);

        $script->add("chown {$this->option('owner')}:{$this->option('group')} -R {$host->base}");

        $script->add("find {$host->base} -type f -exec chmod {$this->option('file')} {} \;");

        $script->add("find {$host->base} -type d -exec chmod {$this->option('directory')} {} \;");

        wait('Resetting permissions...', function() use ($script) {
            $script->execute();
        });

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
