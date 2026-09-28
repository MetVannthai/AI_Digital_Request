<?php

namespace Tests\Unit;

use App\Support\TimeBasedGreeting;
use PHPUnit\Framework\TestCase;

class TimeBasedGreetingTest extends TestCase
{
    public function test_greeting_matches_the_time_of_day(): void
    {
        $this->assertSame('Good night', TimeBasedGreeting::forHour(4));
        $this->assertSame('Good morning', TimeBasedGreeting::forHour(5));
        $this->assertSame('Good morning', TimeBasedGreeting::forHour(11));
        $this->assertSame('Good afternoon', TimeBasedGreeting::forHour(12));
        $this->assertSame('Good afternoon', TimeBasedGreeting::forHour(16));
        $this->assertSame('Good evening', TimeBasedGreeting::forHour(17));
        $this->assertSame('Good evening', TimeBasedGreeting::forHour(20));
        $this->assertSame('Good night', TimeBasedGreeting::forHour(21));
    }
}
