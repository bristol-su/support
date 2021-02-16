<?php

namespace BristolSU\Support\Progress;

use BristolSU\Support\Progress\Contracts\ProgressUpdateContract;
use Illuminate\Support\Facades\Hash;

class ProgressUpdateRepository implements ProgressUpdateContract
{
    /**
     *
     * Generate ItemKey to be used within ProgressHashes Table.
     * This must return a unique value and may require a prefix if used by other models.
     *
     * @param int $id
     * @param string $caller
     * @return string
     */
    protected function generateItemKey(int $id, string $caller): string
    {
        return sprintf('%s_%u', $caller, $id);
    }

    /**
     * @param string $str
     * @return string
     */
    protected function generateHash(string $str): string
    {
        return Hash::make($str);
    }

    /**
     * @param Progress $Activity
     * @return string
     */
    protected function generateActivityString(Progress $Activity): string
    {
        $activityData = [
            $Activity->getActivityInstanceId(),
            $Activity->getPercentage(),
            $Activity->isComplete(),
            json_encode(
                array_map(fn (ModuleInstanceProgress $moduleInstanceProgress): array => [
                    'moduleInstanceId' => $moduleInstanceProgress->getModuleInstanceId(),
                    'mandatory' => $moduleInstanceProgress->isMandatory(),
                    'complete' => $moduleInstanceProgress->isComplete(),
                    'percentage' => $moduleInstanceProgress->getPercentage(),
                    'active' => $moduleInstanceProgress->isActive(),
                    'visible' => $moduleInstanceProgress->isVisible()
                ], $Activity->getModules())
            )
        ];

        $str = '';
        foreach ($activityData as $item) {
            $str .= '_' . $item;
        }

        return $str;
    }

    /**
     * @param string $actual
     * @param string $expected
     * @return bool
     */
    protected function checkHash(string $actual, string $expected): bool
    {
        return ! Hash::check($actual, $expected);
    }

    /**
     * @param int $id
     * @param string $caller
     * @param Progress $currentProgress
     * @return bool
     */
    public function hasChanged(int $id, string $caller, Progress $currentProgress): bool
    {
        $itemKey = $this->generateItemKey($id, $caller);

        $storedProgress = ProgressHash::find($itemKey);

        if (! $storedProgress) {
            return true;
        }

        return $this->checkHash($this->generateActivityString($currentProgress), $storedProgress->hash);
    }

    /**
     * Save Hashed Data into Database.
     *
     * @param int $id
     * @param string $caller
     * @param Progress $currentProgress
     */
    public function saveChanges(int $id, string $caller, Progress $currentProgress): void
    {
        ProgressHash::updateOrcreate(
            [ 'item_key' => $this->generateItemKey($id, $caller) ],
            [ 'hash' => $this->generateHash($this->generateActivityString($currentProgress)) ]
        );
    }
}
