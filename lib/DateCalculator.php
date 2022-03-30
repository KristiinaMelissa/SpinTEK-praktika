<?php

namespace KristiinaMelissa\SpinTekPraktika;

use DateTime;

class DateCalculator
{
    private HolidayHelper $holidayHelper;
    private int $year;

    public function __construct($year)
    {
        $this->holidayHelper = new HolidayHelper($year);
        $this->year = $year;
    }


    public function getPaymentDate($month): DateTime {
        return $this->getFirstWorkday($this->getDefaultPaymentDate($month));
    }

    public function getNotificationDate($month): DateTime {
        return $this->getThreeWorkdaysBefore($this->getPaymentDate($month));
    }

    private function getFirstWorkday($date): DateTime|bool {
        while ($this->isWeekendOrHoliday($date)){
            $date = $date->modify('-1 day');
        }
        return $date;
    }

    private function getThreeWorkdaysBefore($date): DateTime|bool {
        $count = 3;
        while ($count > 0){
            $date = $date->modify('-1 day');
            if (!$this->isWeekendOrHoliday($date)){
                $count--;
            }
        }
        return $date;
    }

    private function getDefaultPaymentDate($month): DateTime|bool {
        return DateTime::createFromFormat('Y-m-d', sprintf('%s-%s-10', $this->year, $month));
    }

    private function getWeekday($date): int {
        return intval($date->format('w'));
    }

    private function isWeekendOrHoliday($date): bool {
        $weekday = $this->getWeekday($date);
        return $weekday < 1 || $weekday > 5 || $this->holidayHelper->isHoliday($date);
    }

}