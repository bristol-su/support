<?php

namespace BristolSU\Support\Progress\Contracts;

use BristolSU\Support\Progress\Progress;

interface ProgressUpdateContract {

    public function generateHash(array $Items): string;

    public function saveHash($Key, array $Hash);

    public function checkHash($expected, $actual): bool;

    public function generateActivityHash(Progress $Activity): string;
}