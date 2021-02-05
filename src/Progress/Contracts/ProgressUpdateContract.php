<?php

namespace BristolSU\Support\Progress\Contracts;

use BristolSU\Support\Progress\Progress;

interface ProgressUpdateContract {

    public function hasChanged($id, $caller, Progress $currentProgress): bool;

    public function saveChanges($id, $caller, Progress $currentProgress): void;
}