<?php

namespace BristolSU\Support\Authorization\Exception;

use Exception;

/**
 * Activity Requires a role. Fired if a role can't be found
 */
class ActivityRequiresRole extends ActivityRequiresParticipant
{
}