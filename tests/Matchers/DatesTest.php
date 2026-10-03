<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Tests\Matchers;

use BinaryStars\Tdd\Matchers\Dates;
use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DatesTest extends TestCase
{
    private Dates $sut;
    private DateTimeImmutable $birthday;
    private DateTimeImmutable $meetingTime;
    private DateTimeImmutable $conferenceStart;
    private DateTimeImmutable $eventTimestamp;
    private DateTimeImmutable $workStart;
    private DateTimeImmutable $projectDeadline;

    protected function setUp(): void
    {
        $this->sut = new Dates();
        $this->birthday = $this->sut->getBirthday();
        $this->meetingTime = $this->sut->getMeetingTime();
        $this->conferenceStart = $this->sut->getConferenceStart();
        $this->eventTimestamp = $this->sut->getEventTimestamp();
        $this->workStart = $this->sut->getWorkStart();
        $this->projectDeadline = $this->sut->getProjectDeadline();
    }

    // date assertions
    #[Test]
    public function shouldBe_1990_05_15(): void
    {
        self::markTestIncomplete('birthday should be 1990-05-15');
    }

    #[Test]
    public function shouldBeBeforeToday(): void
    {
        self::markTestIncomplete('birthday should be before today');
    }

    #[Test]
    public function shouldBeAfter_1980_01_01(): void
    {
        self::markTestIncomplete('birthday should be after 1980-01-01');
    }

    #[Test]
    public function shouldBeInMay(): void
    {
        self::markTestIncomplete('birthday should be in May');
    }

    #[Test]
    public function shouldBeInYear1990(): void
    {
        self::markTestIncomplete('birthday should be in year 1990');
    }

    #[Test]
    public function shouldBeOnDay15(): void
    {
        self::markTestIncomplete('birthday should be on day 15');
    }

    #[Test]
    public function projectDeadlineShouldBeInFuture(): void
    {
        self::markTestIncomplete('project deadline should be in the future compared to 2024-01-01');
    }

    #[Test]
    public function projectDeadlineShouldBeBetweenDates(): void
    {
        self::markTestIncomplete('project deadline should be between 2024-01-01 and 2025-12-31');
    }

    // date + time assertions
    #[Test]
    public function meetingTimeShouldBe_2024_03_20_at_14_30(): void
    {
        self::markTestIncomplete('meeting time should be 2024-03-20T14:30:00');
    }

    #[Test]
    public function meetingTimeShouldBeBeforeNow(): void
    {
        self::markTestIncomplete('meeting time should be before now');
    }

    #[Test]
    public function meetingTimeShouldHaveHour14(): void
    {
        self::markTestIncomplete('meeting time should have hour 14');
    }

    #[Test]
    public function meetingTimeShouldHaveMinute30(): void
    {
        self::markTestIncomplete('meeting time should have minute 30');
    }

    #[Test]
    public function meetingTimeShouldBeInMarch2024(): void
    {
        self::markTestIncomplete('meeting time should be in March 2024');
    }

    // time assertions
    #[Test]
    public function workStartShouldBe_09_00(): void
    {
        self::markTestIncomplete('work start should be 09:00');
    }

    #[Test]
    public function workStartShouldBeBeforeNoon(): void
    {
        self::markTestIncomplete('work start should be before noon (12:00)');
    }

    #[Test]
    public function workStartShouldHaveHour9(): void
    {
        self::markTestIncomplete('work start should have hour 9');
    }

    #[Test]
    public function workStartShouldBeBetween_08_and_10(): void
    {
        self::markTestIncomplete('work start should be between 08:00 and 10:00');
    }

    // time zone assertions
    #[Test]
    public function conferenceStartShouldBeInBerlinTimezone(): void
    {
        self::markTestIncomplete('conference start should be in Europe/Berlin timezone');
    }

    #[Test]
    public function conferenceStartShouldBeCorrectDateTime(): void
    {
        self::markTestIncomplete('conference start should be 2024-06-01T09:00 in Berlin');
    }

    #[Test]
    public function conferenceStartShouldHaveEuropeanOffset(): void
    {
        self::markTestIncomplete('conference start should have zone offset +01:00 or +02:00');
    }

    // timestamp assertions (UTC)
    #[Test]
    public function eventTimestampShouldBeCorrect(): void
    {
        self::markTestIncomplete('event timestamp should be 2024-01-15T10:30:00Z');
    }

    #[Test]
    public function eventTimestampShouldBeInPast(): void
    {
        self::markTestIncomplete('event timestamp should be before now');
    }

    #[Test]
    public function eventTimestampShouldBeCloseToExpected(): void
    {
        self::markTestIncomplete('event timestamp should be close to 2024-01-15T10:30:00Z within 1 second');
    }

    // Advanced: DateInterval
    #[Test]
    public function birthdayShouldBeMoreThan30YearsAgo(): void
    {
        self::markTestIncomplete('birthday should be more than 30 years before today');
    }

    #[Test]
    public function meetingShouldBeAtLeast2HoursAfterNoon(): void
    {
        self::markTestIncomplete('meeting time should be at least 2 hours after 12:00 same day');
    }

    // Combined assertions
    #[Test]
    public function shouldCombineMultipleDateAssertions(): void
    {
        self::markTestIncomplete('TODO: combine multiple date assertions in one test');
    }
}
