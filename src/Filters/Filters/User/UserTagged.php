<?php


namespace BristolSU\Support\Filters\Filters\User;

use BristolSU\ControlDB\Contracts\Models\Tags\UserTag;
use BristolSU\ControlDB\Contracts\Repositories\Tags\UserTag as UserTagRepositoryContract;
use BristolSU\ControlDB\Events\Pivots\Tags\UserUserTag\UserTagged as UserTaggedEvent;
use BristolSU\ControlDB\Events\Pivots\Tags\UserUserTag\UserUntagged as UserUntaggedEvent;
use BristolSU\Support\Filters\Contracts\Filters\UserFilter;
use FormSchema\Schema\Form;

/**
 * Is the user tagged with a tag?
 */
class UserTagged extends UserFilter
{
    /**
     * User Tag Repository, for retrieving all tags as options.
     *
     * @var UserTagRepositoryContract
     */
    private $userTagRepository;

    /**
     * @param UserTagRepositoryContract $userTagRepository User tag repository
     */
    public function __construct(UserTagRepositoryContract $userTagRepository)
    {
        $this->userTagRepository = $userTagRepository;
    }

    /**
     * See if the user is tagged.
     *
     * @param array $settings [ 'tag' => 'full.reference' ]
     *
     * @return bool
     */
    public function evaluate(array $settings): bool
    {
        try {
            $tags = $this->user()->tags();
        } catch (\Exception $e) {
            return false;
        }
        foreach ($tags as $tag) {
            if ($tag->fullReference() === $settings['tag']) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get all user tags as a select list.
     *
     * You should return a form schema which represents the available options for the filter
     *
     * @throws \Exception
     * @return Form Options
     *
     */
    public function options(): Form
    {
        $field = \FormSchema\Generator\Field::select('tag')->setLabel('User Name')->setRequired(true);
        $this->userTagRepository->all()->each(fn(UserTag $userTag) => $field->withOption($userTag->fullReference(), sprintf('%s (%s)', $userTag->name(), $userTag->fullReference()), $userTag->category()->name()));
        return \FormSchema\Generator\Form::make()->withField($field)->getSchema();
    }

    /**
     * Get the filter name.
     *
     * @return string Filter name
     */
    public function name()
    {
        return 'User Tagged';
    }

    /**
     * Get a description of the filter.
     *
     * @return string Filter description
     */
    public function description()
    {
        return 'Returns true if a user is tagged';
    }

    /**
     * Get an alias for the filter.
     *
     * @return string Filter alias
     */
    public function alias()
    {
        return 'user_tagged';
    }

    public static function clearOn(): array
    {
        return [
            UserTaggedEvent::class => fn(UserTaggedEvent $event) => $event->user->id(),
            UserUntaggedEvent::class => fn(UserUntaggedEvent $event) => $event->user->id(),
        ];
    }
}
