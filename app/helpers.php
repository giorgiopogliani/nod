<?php

use App\Support\Spinner;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

function str($str)
{
    return Str::of($str);
}

/**
 * Return path to temp file saved with the contents.
 *
 * @param mixed $contents
 * @return string
 * @throws Exception
 */
function saveStringAsTempFile($contents)
{
    $prefix = Str::random();

    $path = "/tmp/nod.$prefix.temp";

    File::isWritable($path);

    File::put($path, $contents);

    File::chmod($path, 0600);

    return $path;
}
