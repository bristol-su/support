<?php


namespace BristolSU\Support\Control\Contracts\Client;


/**
 * Interface Token
 * @package BristolSU\Support\Control\Contracts\Client
 */
interface Token
{
    /**
     * @param bool $refresh
     * @return mixed
     */
    public function token($refresh = false);
}
