<?php

namespace App;

class Nod
{
    public static function getConfig()
    {
        return json_decode(file_get_contents(getcwd() . '/nod.config.json'));
    }

    public static function validate($config): bool
    {
        return isset($config->host);
    }
}
