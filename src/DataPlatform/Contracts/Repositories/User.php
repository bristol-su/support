<?php


namespace BristolSU\Support\DataPlatform\Contracts\Repositories;


use BristolSU\Support\DataPlatform\Contracts\Models\User as UserModelContract;

/**
 * Interface User
 * @package BristolSU\Support\DataPlatform\Contracts\Repositories
 */
interface User
{

    /**
     * Get a student from the data storage.
     *
     * Get a student using an 'identity', be this email and/or student ID.
     * Throw an exception if not found
     *
     * @param $identity
     * @throws \Exception
     * @return mixed
     */
    public function getByIdentity($identity) : UserModelContract;

    /**
     * @param $email
     * @return UserModelContract
     */
    public function getByEmail($email) : UserModelContract;

    /**
     * @param $studentId
     * @return UserModelContract
     */
    public function getByStudentID($studentId) : UserModelContract;

    /**
     * @param $id
     * @return UserModelContract
     */
    public function getById($id): UserModelContract;
}
