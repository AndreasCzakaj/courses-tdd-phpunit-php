<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Tests\Matchers;

use BinaryStars\Tdd\Matchers\First;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class FilesTest extends TestCase
{
    private string $tmpDir;
    private string $newFile;

    protected function setUp(): void
    {
        // PHPUnit has no built-in temp dir => create a fresh one per test ...
        $this->tmpDir = sys_get_temp_dir() . '/' . uniqid('tdd-', true);
        mkdir($this->tmpDir);
        self::assertDirectoryExists($this->tmpDir);

        $this->newFile = $this->tmpDir . '/newFile.txt';
        self::assertFileDoesNotExist($this->newFile);

        file_put_contents($this->newFile, "The first line\nThe second line\n");
    }

    protected function tearDown(): void
    {
        // ... and clean it up afterwards
        array_map(unlink(...), glob($this->tmpDir . '/*'));
        rmdir($this->tmpDir);
    }

    #[Test]
    public function txtFileShouldExistAndContainFirstAndSecondLine(): void
    {
        // "The first line"
        // "The second line"
        self::markTestIncomplete('newFile should exist and contain the first and second line');
    }

    #[Test]
    public function otherFileShouldNotExist(): void
    {
        self::markTestIncomplete('some other file in the same folder should not exist');
    }

    #[Test]
    public function jsonFile(): void
    {
        self::assertFileExists(First::PPL_JSON);
        $json = file_get_contents(First::PPL_JSON);

        self::assertJson($json);
        self::assertCount(1000, json_decode($json, true));
    }
}
