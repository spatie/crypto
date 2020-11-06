<?php

namespace Spatie\Crypto\Exceptions;

use Exception;

class CouldNotDecryptData extends Exception
{
    public static function make()
    {
        return new static("Could not decrypt the data.");
    }
}
