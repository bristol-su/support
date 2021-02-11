<?php

namespace BristolSU\Support\Progress;

use BristolSU\Support\Progress\Contracts\ProgressUpdateContract;
use BristolSU\Support\Progress\ModuleInstanceProgress;
use Illuminate\Support\Facades\Hash;

class ProgressUpdateRepository implements ProgressUpdateContract {

    /**
     * @param $id
     * @param $caller
     * @return string
     */
    protected function getItemKey($id, $caller): string
    {
        return $this->generateItemKey($id, $caller);
    }

    /**
     * @param $id
     * @param $caller
     * @return string
     */
    protected function generateItemKey($id, $caller): string
    {
        return sprintf("%s_%b", $caller, $id);
    }

    /**
     * @param array $Items
     * @return string
     */
    protected function generateHash(string $str): string
    {
        return Hash::make($str);
    }

    protected function generateActivityString(Progress $Activity): string
    {
        $activityData = [
            $Activity->getActivityInstanceId(),
            $Activity->getPercentage(),
            $Activity->isComplete(),
                json_encode(
                    array_map(fn(ModuleInstanceProgress $moduleInstanceProgress): array => [
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
        foreach ($activityData as $item) { $str .= '_' . $item; }

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

    public function hasChanged($id, $caller, Progress $currentProgress): bool
    {
        // Generate ItemKey:
        $itemKey = $this->getItemKey($id, $caller);

        // Try to retrieve item from Cache:
        $storedProgress = ProgressHashes::find($itemKey);

        // If Item doesn't exist then return true as has changed:
        if(! $storedProgress) {
            return true;
        }

        // If item exists, then check the $currentProgress against the $storedProgress
        return $this->checkHash($this->generateActivityString($currentProgress), $storedProgress->hash);
    }

    /**
     * Save Hashed Data into Database
     *
     * @param $Key
     * @param array $Hash
     */
    public function saveChanges($id, $caller, Progress $currentProgress): void
    {
        ProgressHashes::updateOrcreate(
            [ 'item_key' => $this->getItemKey($id, $caller) ],
            [ 'hash' => $this->generateHash($this->generateActivityString($currentProgress)) ]
        );
    }


}