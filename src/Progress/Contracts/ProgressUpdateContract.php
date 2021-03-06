<?php

namespace BristolSU\Support\Progress\Contracts;

use BristolSU\Support\Progress\Progress;

interface ProgressUpdateContract
{
    /**
     * Checks whether the Progress is hashed and held within the ProgressHashes
     * Table and returns a boolean.
     *
     * @param int $id
     * @param string $caller
     * @param Progress $currentProgress
     * @return bool
     */
    public function hasChanged(int $id, string $caller, Progress $currentProgress): bool;

    /**
     *
     * Generates a new hash of Progress and saves to DB (ProgressHashes Table).
     *
     * @param int $id
     * @param string $caller
     * @param Progress $currentProgress
     */
    public function saveChanges(int $id, string $caller, Progress $currentProgress): void;
}
