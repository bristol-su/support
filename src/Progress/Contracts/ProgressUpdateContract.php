<?php

namespace BristolSU\Support\Progress\Contracts;

use BristolSU\Support\Progress\Progress;

interface ProgressUpdateContract {

    public function getItemKey($id, $caller): string;

    public function generateHash(array $Items): string;

    public function checkHash($expected, $actual): bool;

    public function hasChanged($id, $caller, Progress $currentProgress): bool;

    public function saveChanges($id, $caller, Progress $currentProgress);
}