<?php


namespace BristolSU\Support\Filters\Filters\User;

use BristolSU\ControlDB\Contracts\Repositories\Tags\UserTag as UserTagRepositoryContract;
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
     * @param string $settings [ 'tag' => 'full.reference' ]
     *
     * @return bool
     */
    public function evaluate($settings): bool
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
        $tags = $this->userTagRepository->all();
        $values = [];
        foreach ($tags as $tag) {
            $values[] = [
                'id' => $tag->fullReference(),
                'name' => sprintf('%s (%s)', $tag->name(), $tag->fullReference()),
                'user' => $tag->category()->name()
            ];
        }

        return \FormSchema\Generator\Form::make()->withField(
            \FormSchema\Generator\Field::select('tag')->values($values)->label('User Name')
                ->required(true)
        )->getSchema();
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
}
