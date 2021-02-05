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
        return sprintf("%c_%i", $caller, $id);
    }

    /**
     * @param array $Items
     * @return string
     */
    protected function generateHash(array $Items): string
    {
        $str = '';
        foreach ($Items as $item) { $str .= '_' . $item; }

        return Hash::make($str);
    }

    protected function generateActivityHash(Progress $Activity): string
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
    protected function checkHash($actual, $expected): bool
    {
        return Hash::check($actual, $expected);
    }

    public function hasChanged($id, $caller, Progress $currentProgress): bool
    {
        $itemKey = $this->getItemKey($id, $caller);

        $storedProgress = ProgressHashes::find($id);
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
    public function saveChanges($id, $caller, Progress $currentProgress): void
    {
        ProgressHashes::updateOrcreate(
            [ 'item_key' => $this->getItemKey($id, $caller) ],
            [ 'hash' => $this->generateActivityHash($currentProgress) ]
        );
    }


}