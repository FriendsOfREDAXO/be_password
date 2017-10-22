<?php

namespace BePassword\Services;

class FilterService
{

    public function filterString($value)
    {
        $f = array(chr(0), chr(1), chr(2), chr(3), chr(4), chr(5), chr(6), chr(7),
            chr(8), chr(11), chr(12), chr(14), chr(15), chr(16), chr(17),
            chr(18), chr(19), "\n", "\r");
        return str_replace($f, ' ', $value);
    }

    public function filterText($value)
    {
        $f = array(chr(0), chr(1), chr(2), chr(3), chr(4), chr(5), chr(6), chr(7),
            chr(8), chr(11), chr(12), chr(14), chr(15), chr(16), chr(17),
            chr(18), chr(19));
        return str_replace($f, ' ', stripslashes($value));
    }

    // depreciated
    public function filterTextarea($value)
    {
        return filterText($value);
    }

    public function filterInt($value)
    {
        return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }

    public function filterFloat($value)
    {
        return filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT);
    }

    public function filterEmail($value)
    {
        return filter_var($value, FILTER_SANITIZE_EMAIL);
    }

}


