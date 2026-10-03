<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\FunWithFlags;

class UuidGeneratorNaiveRandomImpl implements UuidGenerator
{
    public function create(): string
    {
        $uuid = '';
        for ($i = 0; $i < 32; $i++) {
            $uuid .= $this->createOne();
        }

        return $uuid;
    }

    private function createOne(): string
    {
        return dechex(random_int(0, 15));
    }
}
