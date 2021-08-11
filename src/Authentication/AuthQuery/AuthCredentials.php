<?php


namespace BristolSU\Support\Authentication\AuthQuery;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class AuthCredentials implements Arrayable, Jsonable, \Stringable
{
    private ?int $groupId;

    private ?int $roleId;

    private ?int $activityInstanceId;

    /**
     * AuthCredentials constructor.
     * @param int|null $groupId
     * @param int|null $roleId
     * @param int|null $activityInstanceId
     */
    public function __construct(
        ?int $groupId = null,
        ?int $roleId = null,
        ?int $activityInstanceId = null
    ) {
        $this->groupId = $groupId;
        $this->roleId = $roleId;
        $this->activityInstanceId = $activityInstanceId;
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->toArray());
    }

    public function toQuery(): string
    {
        return http_build_query(
            $this->toArray(),
            '',
            '&',
            PHP_QUERY_RFC3986
        );
    }

    public function __toString(): string
    {
        return $this->toJson();
    }

    public function toString(): string
    {
        return $this->toJson();
    }

    public function toArray(): array
    {
        return array_filter([
            'g' => $this->groupId,
            'r' => $this->roleId,
            'a' => $this->activityInstanceId,
        ], fn ($value): bool => $value !== null);
    }

    public function groupId()
    {
        return $this->groupId;
    }

    public function roleId()
    {
        return $this->roleId;
    }

    public function activityInstanceId()
    {
        return $this->activityInstanceId;
    }
}
