<?php

namespace App\Commands;

use App\Models\Server;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Console\Question\Question;

class ServerSetup extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'server:setup';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Setup ssh credentials';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Server - e.g. root
        $server = $this->choice('Server', Server::all()->map->name->toArray());

        // username - e.g. root
        $username = $this->ask('Username', 'root');

        // private key
        $question = new Question('The private key to store', null);
        $question->setMultiline(true);
        $key = $this->output->askQuestion($question);

        // check or fail
        $success = Server::where('name', $server)->first()->checkCredentialsAndUpdate($username, $key);

        if ($success) {
            $this->info('Setup successul!');
        }else {
            $this->error('Uh! something is wrong! Check your credentials');
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
