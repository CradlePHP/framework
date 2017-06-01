<?php //-->
/**
 * This file is part of the Cradle PHP Library.
 * (c) 2016-2018 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Cradle\I18n;

use DateTimeZone;
use Cradle\Helper\InstanceTrait;
use Cradle\Resolver\ResolverTrait;

/**
 * Core Factory Class
 *
 * @package  Cradle
 * @category I18n
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Timezone
{
    use ResolverTrait, InstanceTrait;

    /**
     * @const string GMT GMT timezone
     */
    const GMT = 'GMT';

    /**
     * @const string UTC UTC timezone
     */
    const UTC = 'UTC';

    /**
     * @var string|null $offset The offset from the given timezone
     */
    protected $offset = null;

    /**
     * @var int|null $time The time to be manipulated
     */
    protected $time = null;

    /**
     * Preset the timezone and time
     *
     * @param *string         $zone The timezone to use
     * @param int|string|null $time The time to use
     */
    public function __construct(string $zone, $time = null)
    {
        if (is_null($time)) {
            $time = time();
        }

        $this->offset = $this->calculateOffset($zone);
        $this->setTime($time);
    }

    /**
     * Convert current time set here to another time zone
     *
     * @param *string     $zone   valid UTC, GMT, PHP Location or TZ Abbreviation
     * @param string|null $format format
     *
     * @return string|int
     */
    public function convertTo(string $zone, string $format = null)
    {
        $time = $this->time + $this->calculateOffset($zone);

        if (!is_null($format)) {
            return date($format, $time);
        }

        return $time;
    }

    /**
     * Returns the GMT Format
     *
     * @param string $prefix Prefix to add before the returned value
     *
     * @return string
     */
    public function getGMT(string $prefix = self::GMT): string
    {
        list($hour, $minute, $sign) = $this->getUtcParts($this->offset);
        return $prefix.$sign.$hour.$minute;
    }

    /**
     * Returns a list of GMT formats and dates in a 24 hour period
     *
     * @param *string     $format   The format of each date to display
     * @param int         $interval The frequency of rows
     * @param string|null $prefix   The prefix to add before each date display
     *
     * @return array
     */
    public function getGMTDates(
        string $format,
        int $interval = 30,
        string $prefix = self::GMT
    ): array {
        $offsets    = $this->getOffsetDates($format, $interval);
        $dates      = [];

        foreach ($offsets as $offset => $date) {
            list($hour, $minute, $sign) = $this->getUtcParts($offset);
            $gmt = $prefix.$sign.$hour.$minute;
            $dates[$gmt] = $date;
        }

        return $dates;
    }

    /**
     * Returns the current offset of this timezone
     *
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * Returns a list of offsets and dates in a 24 hour period
     *
     * @param *string $format   The format of each date to display
     * @param int     $interval The frequency of rows
     *
     * @return array
     */
    public function getOffsetDates(string $format, int $interval = 30): array
    {
        $dates = [];
        $interval *= 60;

        for ($i=-12*3600; $i <= (12*3600); $i+=$interval) {
            $time = $this->time + $i;
            $dates[$i] = date($format, $time);
        }

        return $dates;
    }

    /**
     * Returns the time or date
     *
     * @param string|null $format Time format
     *
     * @return string|int
     */
    public function getTime(string $format = null)
    {
        $time = $this->time + $this->offset;

        if (!is_null($format)) {
            return date($format, $time);
        }

        return $time;
    }

    /**
     * Returns the UTC Format
     *
     * @param string|null $prefix The prefix to add before the returned value
     *
     * @return string
     */
    public function getUTC(string $prefix = self::UTC): string
    {
        list($hour, $minute, $sign) = $this->getUtcParts($this->offset);
        return $prefix.$sign.$hour.':'.$minute;
    }

    /**
     * Returns a list of UTC formats and dates in a 24 hour period
     *
     * @param *string     $format   The format of each date to display
     * @param int         $interval The frequency of rows
     * @param string|null $prefix   The prefix to add before each date display
     *
     * @return array
     */
    public function getUTCDates(
        string $format,
        int $interval = 30,
        string $prefix = self::UTC
    ): array {
        $offsets    = $this->getOffsetDates($format, $interval);
        $dates      = [];

        foreach ($offsets as $offset => $date) {
            list($hour, $minute, $sign) = $this->getUtcParts($offset);
            $utc = $prefix.$sign.$hour.':'.$minute;
            $dates[$utc] = $date;
        }

        return $dates;
    }

    /**
     * Returns the relative distance
     * $time > this->time = ago
     *
     * @param int|string $time    The time to make relative
     * @param int        $level   The granular level
     * @param string     $default The default date format
     *
     * @return string
     */
    public function toRelative(
        $time = null,
        int $level = 7,
        string $default = 'F d, Y'
    ) {
        //if no time
        if (is_null($time)) {
            //time get now
            $time = time();
        }

        if (is_string($time)) {
            $time = strtotime($time);
        }

        $passed =  $time - $this->time;

        $gravity = [
            'second' => 1,
            'minute' => 60,
            'hour' => 60 * 60,
            'day' => 60 * 60 * 24,
            'week' => 60 * 60 * 24 * 7,
            'month' => 60 * 60 * 24 * 30,
            'year' => 60 * 60 * 24 * 30 * 12
        ];

        $tokens = [];

        $i = 0;
        foreach ($gravity as $magnitude => $distance) {
            if ($i >= $level) {
                break;
            }

            array_unshift($tokens, [$distance, $magnitude]);
            array_push($tokens, [$distance * -1, $magnitude]);

            $i++;
        }

        if ($passed > $tokens[0][0] || $passed < $tokens[count($tokens) - 1][0]) {
            return date($default, $this->time);
        }

        for ($i = 0; $i < count($tokens); $i++) {
            $distance = $tokens[$i][0];
            $relative = $tokens[$i][1];

            if ($passed < $distance) {
                continue;
            }

            if ($distance < 0) {
                $distance = $tokens[$i-1][0];
                $relative = $tokens[$i-1][1];
            }

            $difference = (int) round($passed / $distance);

            if ($relative === 'second' && -5 < $difference && $difference < 5) {
                return 'Now';
            }

            if ($relative === 'day' && $difference === 1) {
                if ($tokens[$i][0] < 0) {
                    return 'Tomorrow';
                }

                return 'Yesterday';
            }

            $suffix = $distance > 0 ? ' ago': ' from now';

            return $difference . ' ' . $relative . ($difference === 1 ? '' : 's') . $suffix;
        }

        return date($default, $this->time);
    }

    /**
     * Sets a new time
     *
     * @param *int|string $time The time value
     *
     * @return Timezone
     */
    public function setTime($time): Timezone
    {
        if (is_string($time)) {
            $time = strtotime($time);
        }

        $this->time = $time - $this->offset;
        return $this;
    }

    /**
     * returns the offset based on timezone
     *
     * @param *string $zone The timezone to calculate the offset with
     *
     * @return string|int
     */
    protected function calculateOffset(string $zone)
    {
        if ($this->resolveStatic(TimezoneValidation::class, 'isLocation', $zone)) {
            return $this->getOffsetFromLocation($zone);
        }

        if ($this->resolveStatic(TimezoneValidation::class, 'isUtc', $zone)) {
            return $this->getOffsetFromUtc($zone);
        }

        if ($this->resolveStatic(TimezoneValidation::class, 'isAbbr', $zone)) {
            return $this->getOffsetFromAbbr($zone);
        }

        return 0;
    }

    /**
     * returns the offset based using the
     * timezone abbreviation
     *
     * @param *string $zone The timezone to calculate the offset with
     *
     * @return string
     */
    protected function getOffsetFromAbbr(string $zone): string
    {
        $zone = timezone_name_from_abbr(strtolower($zone));
        return $this->getOffsetFromLocation($zone);
    }

    /**
     * returns the offset based on location
     *
     * @param *string $zone The timezone to calculate the offset with
     *
     * @return string
     */
    protected function getOffsetFromLocation(string $zone): string
    {
        $zone = new DateTimeZone($zone);
        $gmt = new DateTimeZone(self::GMT);

        return $zone->getOffset(new \DateTime('now', $gmt));
    }

    /**
     * returns the offset based on UTC
     *
     * @param *string $zone The timezone to calculate the offset with
     *
     * @return string|int
     */
    protected function getOffsetFromUtc(string $zone)
    {
        $zone   = str_replace(['GMT','UTC'], '', $zone);
        $zone   = str_replace(':', '', $zone);

        $add    = $zone[0] == '+';
        $zone   = substr($zone, 1);

        switch (strlen($zone)) {
            case 1:
            case 2:
                return $zone * 3600 * ($add?1:-1);
            case 3:
                $hour   = substr($zone, 0, 1) * 3600;
                $minute = substr($zone, 1) * 60;
                return ($hour+$minute) * ($add?1:-1);
            case 4:
                $hour   = substr($zone, 0, 2) * 3600;
                $minute = substr($zone, 2) * 60;
                return ($hour+$minute) * ($add?1:-1);
        }

        return 0;
    }

    /**
     * returns the UTC meta based on offset
     *
     * @param *string $offset Offset to test against
     *
     * @return array
     */
    private function getUtcParts(string $offset): array
    {
        $minute = '0'.(floor(abs($offset/60)) % 60);

        return [
            floor(abs($offset/3600)),
            substr($minute, strlen($minute)-2),
            $offset < 0 ? '-':'+'
        ];
    }
}
