<?php

// The "app": prints a new UUID. Run it with `php bin/uuid.php`.

declare(strict_types=1);

use BinaryStars\Tdd\FunWithFlags\UuidGeneratorNaiveRandomImpl;

require __DIR__ . '/../vendor/autoload.php';

echo (new UuidGeneratorNaiveRandomImpl())->create(), PHP_EOL;
