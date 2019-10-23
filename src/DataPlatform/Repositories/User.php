<?php


namespace BristolSU\Support\DataPlatform\Repositories;


use BristolSU\Support\DataPlatform\Contracts\Models\User as UserModelContract;
use BristolSU\Support\DataPlatform\Contracts\Repositories\User as UserRepositoryContract;
use Twigger\UnionCloud\API\Exception\Resource\ResourceNotFoundException;
use Twigger\UnionCloud\API\UnionCloud;

/**
 * Class User
 * @package BristolSU\Support\DataPlatform\Repositories
 */
class User implements UserRepositoryContract
{

    /**
     * @var UnionCloud
     */
    private $unionCloud;

    /**
     * User constructor.
     * @param UnionCloud $unionCloud
     */
    public function __construct(UnionCloud $unionCloud)
    {
        $this->unionCloud = $unionCloud;
    }

    /**
     * @param $identity
     * @return UserModelContract
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Twigger\UnionCloud\API\Exception\Authentication\AuthenticatorNotFound
     * @throws \Twigger\UnionCloud\API\Exception\Request\RequestHistoryNotFound
     * @throws \Twigger\UnionCloud\API\Exception\Response\BaseResponseException
     * @throws \Twigger\UnionCloud\API\Exception\Response\ResponseMustInheritIResponse
     */
    public function getByIdentity($identity) : UserModelContract
    {
        try {
            return $this->getByEmail($identity);
        } catch (ResourceNotFoundException $e) {
            return $this->getByStudentID($identity);
        }
    }

    /**
     * @param $email
     * @return UserModelContract
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Twigger\UnionCloud\API\Exception\Authentication\AuthenticatorNotFound
     * @throws \Twigger\UnionCloud\API\Exception\Request\RequestHistoryNotFound
     * @throws \Twigger\UnionCloud\API\Exception\Response\BaseResponseException
     * @throws \Twigger\UnionCloud\API\Exception\Response\ResponseMustInheritIResponse
     */
    public function getByEmail($email): UserModelContract
    {
        $user = $this->unionCloud->users()->search(['email' => $email], true)->get()->first();
        return new \BristolSU\Support\DataPlatform\Models\User($user->getOriginalAttributes());
    }

    /**
     * @param $studentId
     * @return UserModelContract
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Twigger\UnionCloud\API\Exception\Authentication\AuthenticatorNotFound
     * @throws \Twigger\UnionCloud\API\Exception\Request\RequestHistoryNotFound
     * @throws \Twigger\UnionCloud\API\Exception\Response\BaseResponseException
     * @throws \Twigger\UnionCloud\API\Exception\Response\ResponseMustInheritIResponse
     */
    public function getByStudentID($studentId): UserModelContract
    {
        $user = $this->unionCloud->users()->search(['id' => $studentId])->get()->first();
        return new \BristolSU\Support\DataPlatform\Models\User($user->getOriginalAttributes());
    }

    /**
     * @param $id
     * @return UserModelContract
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Twigger\UnionCloud\API\Exception\Authentication\AuthenticatorNotFound
     * @throws \Twigger\UnionCloud\API\Exception\Request\RequestHistoryNotFound
     * @throws \Twigger\UnionCloud\API\Exception\Response\BaseResponseException
     * @throws \Twigger\UnionCloud\API\Exception\Response\ResponseMustInheritIResponse
     */
    public function getById($id): UserModelContract
    {
        $user = $this->unionCloud->users()->getByUID($id)->get()->first();
        return new \BristolSU\Support\DataPlatform\Models\User($user->getOriginalAttributes());
    }
}
