<?php

namespace FriendsOfRedaxo\BePassword\Services;

class FilterService
{

    public function filterString(string $value): string
    {
        $f = [chr(0), chr(1), chr(2), chr(3), chr(4), chr(5), chr(6), chr(7),
            chr(8), chr(11), chr(12), chr(14), chr(15), chr(16), chr(17),
            chr(18), chr(19), "\n", "\r"];
        return str_replace($f, ' ', $value);
    }

    public function filterText(string $value): string
    {
        $f = [chr(0), chr(1), chr(2), chr(3), chr(4), chr(5), chr(6), chr(7),
            chr(8), chr(11), chr(12), chr(14), chr(15), chr(16), chr(17),
            chr(18), chr(19)];
        return str_replace($f, ' ', stripslashes($value));
    }

    // deprecated
    public function filterTextarea(string $value): string
    {
        return $this->filterText($value);
    }

    public function filterInt(mixed $value): int
    {
        return (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }

    public function filterFloat(mixed $value): float
    {
        return (float) filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT);
    }

    public function filterEmail(string $value): string|false
    {
        return filter_var($value, FILTER_SANITIZE_EMAIL);
    }

}


