<?php

namespace BristolSU\Support\Authorization\Exception;

use BristolSU\Support\Activity\Activity;

/**
 * Activity Requires a user. Fired if a user can't be found.
 */
class ActivityRequiresUser extends ActivityRequiresParticipant
{
}
