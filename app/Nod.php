<?php

namespace App;

class Nod
{
    public static function validate($config): bool
    {
        return isset($config->host);
    }
}
