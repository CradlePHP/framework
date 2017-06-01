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

/**
 * Timezone Validation Class
 *
 * @package  Cradle
 * @category I18n
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class TimezoneValidation
{
    /**
     * Validates that value is a proper abbreviation
     *
     * @param *scalar $value The value to test against
     *
     * @return bool
     */
    public static function isAbbr($value): bool
    {
        return preg_match('/^[A-Z]{1,5}$/', $value);
    }

    /**
     * Validates that value is a proper location
     *
     * @param *scalar $value The value to test against
     *
     * @return bool
     */
    public static function isLocation($value): bool
    {
        return in_array($value, DateTimeZone::listIdentifiers());
    }

    /**
     * Validates that value is a proper UTC
     *
     * @param *scalar $value The value to test against
     *
     * @return bool
     */
    public static function isUtc($value): bool
    {
        return preg_match('/^(GMT|UTC){0,1}(\-|\+)[0-9]{1,2}(\:{0,1}[0-9]{2}){0,1}$/', $value);
    }
}
