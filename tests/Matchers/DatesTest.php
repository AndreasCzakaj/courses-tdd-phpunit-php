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

    // Mind the order of the args: assertGreaterThan($expected, $actual)
    // reads "assert that $actual is greater than $expected"

    // date assertions
    #[Test]
    public function shouldBe_1990_05_15(): void
    {
        self::assertEquals(new DateTimeImmutable('1990-05-15'), $this->birthday);
    }

    #[Test]
    public function shouldBeBeforeToday(): void
    {
        self::assertLessThan(new DateTimeImmutable('today'), $this->birthday);
    }

    #[Test]
    public function shouldBeAfter_1980_01_01(): void
    {
        self::assertGreaterThan(new DateTimeImmutable('1980-01-01'), $this->birthday);
    }

    #[Test]
    public function shouldBeInMay(): void
    {
        self::assertSame('05', $this->birthday->format('m'));
    }

    #[Test]
    public function shouldBeInYear1990(): void
    {
        self::assertSame('1990', $this->birthday->format('Y'));
    }

    #[Test]
    public function shouldBeOnDay15(): void
    {
        self::assertSame('15', $this->birthday->format('d'));
    }

    #[Test]
    public function projectDeadlineShouldBeInFuture(): void
    {
        self::assertGreaterThan(new DateTimeImmutable('2024-01-01'), $this->projectDeadline);
    }

    #[Test]
    public function projectDeadlineShouldBeBetweenDates(): void
    {
        self::assertGreaterThanOrEqual(new DateTimeImmutable('2024-01-01'), $this->projectDeadline);
        self::assertLessThanOrEqual(new DateTimeImmutable('2025-12-31'), $this->projectDeadline);
    }

    // date + time assertions
    #[Test]
    public function meetingTimeShouldBe_2024_03_20_at_14_30(): void
    {
        self::assertEquals(new DateTimeImmutable('2024-03-20 14:30:00'), $this->meetingTime);
    }

    #[Test]
    public function meetingTimeShouldBeBeforeNow(): void
    {
        self::assertLessThan(new DateTimeImmutable('now'), $this->meetingTime);
    }

    #[Test]
    public function meetingTimeShouldHaveHour14(): void
    {
        self::assertSame('14', $this->meetingTime->format('H'));
    }

    #[Test]
    public function meetingTimeShouldHaveMinute30(): void
    {
        self::assertSame('30', $this->meetingTime->format('i'));
    }

    #[Test]
    public function meetingTimeShouldBeInMarch2024(): void
    {
        self::assertSame('2024-03', $this->meetingTime->format('Y-m'));
    }

    // time assertions
    #[Test]
    public function workStartShouldBe_09_00(): void
    {
        self::assertSame('09:00', $this->workStart->format('H:i'));
    }

    #[Test]
    public function workStartShouldBeBeforeNoon(): void
    {
        self::assertLessThan('12:00', $this->workStart->format('H:i'));
    }

    #[Test]
    public function workStartShouldHaveHour9(): void
    {
        self::assertSame(9, (int) $this->workStart->format('G'));
    }

    #[Test]
    public function workStartShouldBeBetween_08_and_10(): void
    {
        self::assertThat($this->workStart->format('H:i'), self::logicalAnd(
            self::greaterThanOrEqual('08:00'),
            self::lessThanOrEqual('10:00'),
        ));
    }

    // time zone assertions
    #[Test]
    public function conferenceStartShouldBeInBerlinTimezone(): void
    {
        self::assertSame('Europe/Berlin', $this->conferenceStart->getTimezone()->getName());
    }

    #[Test]
    public function conferenceStartShouldBeCorrectDateTime(): void
    {
        self::assertEquals(
            new DateTimeImmutable('2024-06-01 09:00:00', new DateTimeZone('Europe/Berlin')),
            $this->conferenceStart,
        );
        // dates are compared as points in time: 09:00 in Berlin is 07:00 UTC in summer
        self::assertEquals(new DateTimeImmutable('2024-06-01T07:00:00Z'), $this->conferenceStart);
    }

    #[Test]
    public function conferenceStartShouldHaveEuropeanOffset(): void
    {
        self::assertContains($this->conferenceStart->format('P'), ['+01:00', '+02:00']);
    }

    // timestamp assertions (UTC)
    #[Test]
    public function eventTimestampShouldBeCorrect(): void
    {
        self::assertEquals(new DateTimeImmutable('2024-01-15T10:30:00Z'), $this->eventTimestamp);
    }

    #[Test]
    public function eventTimestampShouldBeInPast(): void
    {
        self::assertLessThan(new DateTimeImmutable('now'), $this->eventTimestamp);
    }

    #[Test]
    public function eventTimestampShouldBeCloseToExpected(): void
    {
        // for dates, the delta is in seconds
        self::assertEqualsWithDelta(new DateTimeImmutable('2024-01-15T10:30:00Z'), $this->eventTimestamp, 1);
    }

    // Advanced: DateInterval
    #[Test]
    public function birthdayShouldBeMoreThan30YearsAgo(): void
    {
        self::assertLessThan(new DateTimeImmutable('today -30 years'), $this->birthday);
        // alternative
        self::assertGreaterThan(30, $this->birthday->diff(new DateTimeImmutable('today'))->y);
    }

    #[Test]
    public function meetingShouldBeAtLeast2HoursAfterNoon(): void
    {
        $noon = $this->meetingTime->setTime(12, 0);
        $seconds = $this->meetingTime->getTimestamp() - $noon->getTimestamp();
        self::assertGreaterThanOrEqual(2 * 60 * 60, $seconds);
    }

    // Combined assertions
    #[Test]
    public function shouldCombineMultipleDateAssertions(): void
    {
        self::assertThat($this->birthday, self::logicalAnd(
            self::equalTo(new DateTimeImmutable('1990-05-15')),
            self::lessThan(new DateTimeImmutable('today')),
            self::greaterThan(new DateTimeImmutable('1980-01-01')),
        ));
        self::assertSame('1990-05-15', $this->birthday->format('Y-m-d'));
    }
}
