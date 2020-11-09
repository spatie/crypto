<?php

namespace Spatie\Crypto\Rsa\Exceptions;

use Exception;

class FileDoesNotExist extends Exception
{
    public static function make(string $path): self
    {
        return new self("There is no file at path: `{$path}`.");
    }
}
