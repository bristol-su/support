<?php


namespace BristolSU\Support\Filters\Filters\Role;


use BristolSU\ControlDB\Contracts\Repositories\Tags\RoleTag as RoleTagRepositoryContract;
use BristolSU\Support\Filters\Contracts\Filters\RoleFilter;
use FormSchema\Schema\Form;

/**
 * Is the role tagged with a tag?
 */
class RoleTagged extends RoleFilter
{

    /**
     * Role Tag Repository, for retrieving all tags as options
     * 
     * @var RoleTagRepositoryContract
     */
    private $roleTagRepository;

    /**
     * @param RoleTagRepositoryContract $roleTagRepository Role tag repository
     */
    public function __construct(RoleTagRepositoryContract $roleTagRepository)
    {
        $this->roleTagRepository = $roleTagRepository;
    }

    /**
     * See if the role is tagged
     * 
     * @param string $settings [ 'tag' => 'full.reference' ]
     * 
     * @return bool
     */
    public function evaluate($settings): bool
    {
        try {
            $tags = $this->role()->tags();
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
     * Get all role tags as a select list
     *
     * You should return a form schema which represents the available options for the filter
     *
     * @return Form Options
     *
     * @throws \Exception
     */
    public function options(): Form
    {
        $tags = $this->roleTagRepository->all();
        $values = [];
        foreach ($tags as $tag) {
            $values[] = [
                'id' => $tag->fullReference(),
                'name' => sprintf('%s (%s)', $tag->name(), $tag->fullReference()),
                'role' => $tag->category()->name()
            ];
        }
        return \FormSchema\Generator\Form::make()->withField(
            \FormSchema\Generator\Field::select('tag')->values($values)->label('Role Name')
                ->required(true)
        )->getSchema();
    }

    /**
     * Get the filter name
     * 
     * @return string Filter name
     */
    public function name()
    {
        return 'Role Tagged';
    }

    /**
     * Get a description of the filter
     * 
     * @return string Filter description
     */
    public function description()
    {
        return 'Returns true if a role is tagged';
    }

    /**
     * Get an alias for the filter
     * 
     * @return string Filter alias
     */
    public function alias()
    {
        return 'role_tagged';
    }
}
