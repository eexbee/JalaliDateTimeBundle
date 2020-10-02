<?php

namespace Eexbee\JalaliDateTimeBundle\Tests;

use Eexbee\JalaliDateTimeBundle\Service\DateTimeService;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
    public function testGregorianToJalali()
    {
        $service = new DateTimeService();
        $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', "2020-04-04 11:08:10");
        $jalali = $service->gregorianToJalaliFromDateTimeObject($dateTime, "-");
        $this->assertEquals("1399-1-16", $jalali);
    }

    public function testDate()
    {
        $service = new DateTimeService();
        $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', "2020-04-04 11:08:10");
        $date = $service->date('Y-m-d H:i:s', $dateTime->getTimestamp(), "Asia/Tehran", 'en');
        $this->assertEquals("1399-01-16 11:08:10", $date);
        //TODO: test more formats
    }

    public function testJalaliToGregorian()
    {
        $service = new DateTimeService();
        $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', "2020-04-04 11:08:10");
        $date = $service->jalaliToGregorian(
            "1399",
            "01",
            "16",
            "-"
        );
        $this->assertEquals($dateTime->format("Y-m-d"), $date);
    }

    public function testMakeTime()
    {
        $service = new DateTimeService();
        $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', "2020-04-04 11:08:10");
        $timestamp = $service->makeTime(
            11,
            8,
            10,
            1,
            16,
            1399
        );
        $this->assertEquals($dateTime->getTimestamp(), $timestamp);
    }
}
