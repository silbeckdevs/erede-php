<?php

namespace Rede\Tests;

use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    /**
     * @return string|resource|null
     */
    protected function getMockFileContents(string $filename = '')
    {
        $filePath = __DIR__ . "/mocks/{$filename}";

        if (!file_exists($filePath)) {
            throw new \RuntimeException("Mock file not found: $filePath");
        }

        if (str_ends_with($filename, '.php')) {
            return require $filePath;
        }

        return file_get_contents($filePath) ?: null;
    }
}
