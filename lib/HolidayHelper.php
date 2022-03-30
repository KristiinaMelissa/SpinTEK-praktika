<?php

namespace KristiinaMelissa\SpinTekPraktika;

use DateTime;

class HolidayHelper
{
    private static array $holidayList;

    public function __construct($year)
    {
        self::$holidayList = HolidayHelper::getHolidayList($year);
    }

    private static function getHolidayList($year) : array {
        $url = sprintf('https://date.nager.at/api/v3/PublicHolidays/%s/EE?fbclid=IwAR30sVwZ-XKEo06U7aBwr48sQLv60tFBIDnIO5YNM-hjOfO2jrYuLUofQKU', $year);
        $json = file_get_contents($url);
        $publicHolidays = json_decode($json);
        $holidayDates = [];

        foreach ($publicHolidays as $holiday){
            $date = DateTime::createFromFormat('Y-m-d', $holiday->date);
            $holidayDates[] = $date;
        }

        return $holidayDates;
    }

    public static function isHoliday($date) : bool {
        foreach (self::$holidayList as $holiday){
            if ($date == $holiday){
                return true;
            }
        }
        return false;
    }

}