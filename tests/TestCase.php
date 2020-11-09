<?php

namespace Spatie\Crypto\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{
    public function getStub(string $nameOfStub): string
    {
        return __DIR__ . "/stubs/{$nameOfStub}";
    }

    public function getTempPath(string $fileName): string
    {
        return __DIR__ . "/temp/{$fileName}";
    }
}
