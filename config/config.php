<?php

use BristolSU\Support\Filters\Filters\GroupTagged;
use BristolSU\Support\Filters\Filters\UserEmailIs;
use BristolSU\Support\Permissions\Testers\CheckPermissionExists;
use BristolSU\Support\Permissions\Testers\ModuleInstanceAdminPermissions;
use BristolSU\Support\Permissions\Testers\ModuleInstanceUserPermissions;
use BristolSU\Support\Permissions\Testers\SystemLogicPermission;
use BristolSU\Support\Permissions\Testers\SystemUserPermission;

return [
    'filters' => [
        'group_tagged' => GroupTagged::class,
        'user_email' => UserEmailIs::class
    ],
    
    'permissions' => [
        'testers' => [
            SystemUserPermission::class,
            SystemLogicPermission::class,
            ModuleInstanceUserPermissions::class,
            ModuleInstanceAdminPermissions::class,
            CheckPermissionExists::class
        ]
    ]
];