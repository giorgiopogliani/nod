<?php

namespace App\Support;

class Spinner
{
    protected $message;

    protected $callback;

    protected $chars = ['⠇ ', '⠋ ', '⠙ ', '⠸ ', '⠴ ', '⠦ '];

    /** @var LoopInterface */
    protected $loop;

    protected $last = 0;

    public function __construct()
    {
        $this->loop = \React\EventLoop\Factory::create();
    }

    public function start($message)
    {
        $this->message = $message;

        $this->loop->addPeriodicTimer(1 / 30, function () {
            $buffer = fopen('php://output', 'w');

            fwrite($buffer, str_repeat(' ', strlen($this->last)) . "\r");

            fwrite($buffer, current($this->chars) . $this->message);

            $this->last = strlen(current($this->chars) . $this->message);

            next($this->chars);

            if (!current($this->chars)) {
                reset($this->chars);
            }

            fclose($buffer);
        });
    }

    public function callback($callback)
    {
        $this->callback = $callback;
    }

    public function run()
    {
        $pid = pcntl_fork();

        if ($pid == -1) {
            die('could not fork');
        } else if ($pid) {
            pcntl_signal(SIGCHLD, function() {
                $this->loop->stop();

                $buffer = fopen('php://output', 'w');

                fwrite($buffer, str_repeat(' ', strlen($this->last)) . "\r");
                fclose($buffer);
                echo ("✓ $this->message");
                echo PHP_EOL;
            });

            // we are the parent
            $this->loop->run();
        } else {
            call_user_func($this->callback);
            exit(0);
        }
    }
}
