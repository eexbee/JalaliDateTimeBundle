<?php

namespace Eexbee\JalaliDateTimeBundle\Twig;

use Eexbee\JalaliDateTimeBundle\Service\DateTimeService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DateTimeExtension extends AbstractExtension
{
    private $dateTime;

    public function __construct(DateTimeService $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('j_datetime_format', [$this, 'dateTime']),
        ];
    }

    public function dateTime(
        \DateTime $dateTime,
        $format = 'H:i ,d F Y',
        $timeZone = 'Asia/Tehran',
        $lang = 'fa'
    ) : string {
        return $this->dateTime->date($format, $dateTime->getTimestamp(), $timeZone, $lang);
    }
}
