<?php

namespace FriendsOfRedaxo\BePassword\Services;

class RandomService
{
    public function createToken() :string
    {
        return bin2hex(random_bytes(32));
    }
}
