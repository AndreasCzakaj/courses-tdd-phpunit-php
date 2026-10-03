<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Matchers;

use DateTimeImmutable;
use DateTimeZone;

class Dates
{
    public function getBirthday(): DateTimeImmutable
    {
        return new DateTimeImmutable('1990-05-15');
    }

    public function getMeetingTime(): DateTimeImmutable
    {
        return new DateTimeImmutable('2024-03-20 14:30:00');
    }

    public function getConferenceStart(): DateTimeImmutable
    {
        return new DateTimeImmutable('2024-06-01 09:00:00', new DateTimeZone('Europe/Berlin'));
    }

    public function getEventTimestamp(): DateTimeImmutable
    {
        return new DateTimeImmutable('2024-01-15T10:30:00Z');
    }

    /** PHP has no "time only" type => it's 09:00 on 1970-01-01 */
    public function getWorkStart(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat('!H:i', '09:00');
    }

    public function getProjectDeadline(): DateTimeImmutable
    {
        return new DateTimeImmutable('2024-12-31');
    }
}
