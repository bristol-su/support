<?php

namespace BristolSU\Support\Progress;

use BristolSU\Support\Progress\Contracts\ProgressUpdateContract;
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