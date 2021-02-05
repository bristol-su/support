<?php

namespace BristolSU\Support\Progress\Contracts;

use BristolSU\Support\Progress\Progress;

interface ProgressUpdateContract {

    public function generateItemKey($id, $caller): string;

    public function generateHash(array $Items): string;

    public function checkHash($expected, $actual): bool;

    public function hasChanged($itemKey, Progress $currentProgress): bool;

    public function saveChanges($Key, Progress $currentProgress);
}