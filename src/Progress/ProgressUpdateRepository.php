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
    public function generateItemKey($id, $caller): string
    {
        return sprintf("%c_%i", $caller, $id);
    }

    /**
     * @param array $Items
     * @return string
     */
    public function generateHash(array $Items): string
    {
        $str = '';
        foreach ($Items as $item) { $str .= '_' . $item; }

        return Hash::make($str);
    }

    public function generateActivityHash(Progress $Activity): string
    {
        return $this->generateHash([
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
        ]);
    }

    /**
     * @param $actual
     * @param $expected
     * @return bool
     */
    public function checkHash($actual, $expected): bool
    {
        return \Hash::check($actual, $expected);
    }

    public function hasChanged($itemKey, Progress $currentProgress): bool
    {
        $storedProgress = ProgressHashes::find($itemKey);
        // Check if $itemKey exists:
        if(! $storedProgress) {
            return true;
        }

        // TODO : Check this as I think that it needs the Hash or the Array passing through to work properly!
        // If exists check current against stored:
        return $this->checkHash($this->generateActivityHash($currentProgress), $storedProgress);
    }

    /**
     * Save Hashed Data into Database
     *
     * @param $Key
     * @param array $Hash
     */
    public function saveChanges($Key, Progress $currentProgress)
    {
        ProgressHashes::updateOrcreate(
            [ 'item_key' => $Key ],
            [ 'hash' => $this->generateActivityHash($currentProgress) ]
        );
    }


}