<?php

namespace BristolSU\Support\Progress;

use BristolSU\Support\Progress\Contracts\ProgressUpdateContract;
use BristolSU\Support\Progress\ModuleInstanceProgress;
use Illuminate\Support\Facades\Hash;

class ProgressUpdateRepository implements ProgressUpdateContract {

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
     * Save Hashed Data into Database
     *
     * @param $Key
     * @param array $Hash
     */
    public function saveHash($Key, array $Hash)
    {
        ProgressHashes::updateOrcreate(
            [ 'item_key' => $Key ],
            [ 'hash' => $this->generateHash($Hash) ]
        );
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


}