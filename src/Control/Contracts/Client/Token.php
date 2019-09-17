<?php


namespace BristolSU\Support\Control\Contracts\Client;


interface Token
{
    public function token($refresh = false);
}
