<?php

namespace BristolSU\Support\Logic\DatabaseDecorator;

use BristolSU\ControlDB\Contracts\Models\User;
use BristolSU\ControlDB\Contracts\Models\Group;
use BristolSU\ControlDB\Contracts\Models\Role;
use BristolSU\Support\Logic\Logic;
use Database\Factories\LogicResultFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogicResult extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;

    protected $table = 'logic_results';

    protected $fillable = [
        'logic_id', 'user_id', 'group_id', 'role_id', 'result'
    ];

    protected $casts = [
        'result' => 'bool'
    ];

    public function scopeForLogic(Builder $query, Logic $logic)
    {
        $query->where('logic_id', $logic->id);
    }

    public function scopeWithResources(Builder $query, User $user, ?Group $group = null, ?Role $role = null)
    {
        $query->where([
            'user_id' => $user->id(),
            'group_id' => $group?->id(),
            'role_id' => $role?->id(),
        ]);
    }

    public function getResult(): bool
    {
        return (bool) $this->result;
    }

    public function hasGroup(): bool
    {
        return $this->group_id !== null;
    }

    public function hasRole(): bool
    {
        return $this->role_id !== null;
    }

    public function getGroupId(): ?int
    {
        return $this->group_id;
    }

    public function getRoleId(): ?int
    {
        return $this->role_id;
    }

    public static function addResult(bool $result, Logic $logic, User $user, ?Group $group = null, ?Role $role = null)
    {
        return self::create([
            'result' => $result,
            'logic_id' => $logic->id,
            'user_id' => $user->id(),
            'group_id' => $group?->id(),
            'role_id' => $role?->id()
        ]);
    }

    protected static function newFactory()
    {
        return new LogicResultFactory();
    }
}
