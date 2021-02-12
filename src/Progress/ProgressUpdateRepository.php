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
     * @param $id
     * @param $caller
     * @return string
     */
    protected function generateItemKey($id, $caller): string
    {
        return sprintf('%s_%u', $caller, $id);
    }

    /**
     * @param array $Items
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
     * @param $actual
     * @param $expected
     * @return bool
     */
    protected function checkHash($actual, $expected): bool
    {
        return ! Hash::check($actual, $expected);
    }

    /**
     * @param int $id
     * @param string $caller
     * @param Progress $currentProgress
     * @return bool
     */
    public function hasChanged($id, $caller, Progress $currentProgress): bool
    {
        $itemKey = $this->generateItemKey($id, $caller);

        $storedProgress = ProgressHashes::find($itemKey);

        if (! $storedProgress) {
            return true;
        }

        return $this->checkHash($this->generateActivityString($currentProgress), $storedProgress->hash);
    }

    /**
     * Save Hashed Data into Database.
     *
     * @param $Key
     * @param array $Hash
     * @param mixed $id
     * @param mixed $caller
     */
    public function saveChanges($id, $caller, Progress $currentProgress): void
    {
        ProgressHashes::updateOrcreate(
            [ 'item_key' => $this->generateItemKey($id, $caller) ],
            [ 'hash' => $this->generateHash($this->generateActivityString($currentProgress)) ]
        );
    }
}
