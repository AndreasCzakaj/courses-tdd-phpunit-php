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
        self::assertFileExists($this->newFile);
        self::assertFileIsReadable($this->newFile);

        $content = file_get_contents($this->newFile);
        self::assertStringContainsString('The first line', $content);
        self::assertStringContainsString('The second line', $content);

        // whole content in one go
        self::assertStringEqualsFile($this->newFile, "The first line\nThe second line\n");
    }

    #[Test]
    public function otherFileShouldNotExist(): void
    {
        self::assertFileDoesNotExist($this->tmpDir . '/otherFile.txt');
        self::assertCount(1, glob($this->tmpDir . '/*'));
    }

    #[Test]
    public function jsonFile(): void
    {
        self::assertFileExists(First::PPL_JSON);
        $json = file_get_contents(First::PPL_JSON);

        self::assertJson($json);
        $people = json_decode($json, true);
        self::assertCount(1000, $people);

        // JSON is parsed into arrays, which are compared by value
        self::assertSame(
            [
                'id' => 18,
                'firstName' => 'Crawford',
                'lastName' => 'Roisen',
                'email' => 'croisenh@independent.co.uk',
                'ipAddress' => '163.170.23.182',
            ],
            $people[17],
        );

        // or compare JSON with JSON: ignores formatting and key order
        self::assertJsonStringEqualsJsonString(
            '{"firstName": "Crawford", "id": 18, "lastName": "Roisen",
              "email": "croisenh@independent.co.uk", "ipAddress": "163.170.23.182"}',
            json_encode($people[17]),
        );
    }
}
