<?php

namespace FriendsOfRedaxo\BePassword\Services;

use function chr;

use const FILTER_SANITIZE_EMAIL;
use const FILTER_SANITIZE_NUMBER_FLOAT;
use const FILTER_SANITIZE_NUMBER_INT;

class FilterService
{
    /**
     * @api
     * @param string $value 
     * @return string 
     */
    public function filterString(string $value): string
    {
        $f = [chr(0), chr(1), chr(2), chr(3), chr(4), chr(5), chr(6), chr(7),
            chr(8), chr(11), chr(12), chr(14), chr(15), chr(16), chr(17),
            chr(18), chr(19), "\n", "\r"];
        return str_replace($f, ' ', $value);
    }

    /**
     * @api
     * @param string $value 
     * @return string 
     */
    public function filterText(string $value): string
    {
        $f = [chr(0), chr(1), chr(2), chr(3), chr(4), chr(5), chr(6), chr(7),
            chr(8), chr(11), chr(12), chr(14), chr(15), chr(16), chr(17),
            chr(18), chr(19)];
        return str_replace($f, ' ', stripslashes($value));
    }

    /**
     * @api
     * @param string $value 
     * @return string 
     * @deprecated use filterText instead
     */
    public function filterTextarea(string $value): string
    {
        return $this->filterText($value);
    }

    /**
     * @api
     * @param mixed $value 
     * @return int 
     */
    public function filterInt(mixed $value): int
    {
        return (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * @api
     * @param mixed $value 
     * @return float 
     */
    public function filterFloat(mixed $value): float
    {
        return (float) filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT);
    }

    /**
     * @api
     * @param string $value 
     * @return string|false 
     */
    public function filterEmail(string $value): string|false
    {
        return filter_var($value, FILTER_SANITIZE_EMAIL);
    }
}
