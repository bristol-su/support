<?php

namespace BristolSU\Support\Progress\Contracts;

interface ProgressUpdateContract {

    public function generateHash(array $Items): string;

    public function saveHash($Key, array $Hash);

    public function checkHash($expected, $actual): bool;
}