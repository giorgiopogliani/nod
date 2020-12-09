<?php

namespace App\Support;

use App\Models\Server;

class Script
{
    protected $server;

    protected $commands = [];

    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    public function add(string $command)
    {
        $this->commands[] = $command;

        return $this;
    }

    public function execute()
    {
        $text = implode("\n", $this->commands);

        $this->server->exec(<<<TXT
        '
        $text
        '
        TXT);
    }
}
