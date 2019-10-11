<?php


namespace BristolSU\Support\Tests\Module;


use BristolSU\Support\Completion\Contracts\EventRepository;
use BristolSU\Support\Module\Module;
use BristolSU\Support\Permissions\Contracts\PermissionRepository;
use Illuminate\Config\Repository;
use BristolSU\Support\Tests\TestCase;

class ModuleTest extends TestCase
{

    /** @test */
    public function alias_has_setters_and_getters(){
        $module = new Module;
        $module->setAlias('alias1');
        $this->assertEquals('alias1', $module->getAlias());
    }

    /** @test */
    public function name_has_setters_and_getters(){
        $module = new Module;
        $module->setName('name1');
        $this->assertEquals('name1', $module->getName());
    }

    /** @test */
    public function description_has_setters_and_getters(){
        $module = new Module;
        $module->setDescription('description1');
        $this->assertEquals('description1', $module->getDescription());
    }

    /** @test */
    public function permissions_have_setters_and_getters(){
        $module = new Module;
        $module->setPermissions([
            'permission1'
        ]);
        $this->assertEquals(['permission1'], $module->getPermissions());
    }

    /** @test */
    public function settings_have_setters_and_getters(){
        $module = new Module;
        $module->setSettings([
            'setting1'
        ]);
        $this->assertEquals(['setting1'], $module->getSettings());
    }

    /** @test */
    public function completionEvents_have_setters_and_getters(){
        $module = new Module;
        $module->setCompletionEvents([
            'completionEvent1'
        ]);
        $this->assertEquals(['completionEvent1'], $module->getCompletionEvents());
    }

    /** @test */
    public function toArray_returns_an_array_of_parameters(){
        $module = new Module;
        $module->setAlias('alias1');
        $module->setName('name1');
        $module->setDescription('description1');
        $module->setPermissions(['permission1']);
        $module->setSettings(['setting1']);
        $module->setCompletionEvents(['completionEvent1']);
        
        $this->assertEquals([
            'alias' => 'alias1',
            'name' => 'name1',
            'description' => 'description1',
            'permissions' => ['permission1'],
            'settings' => ['setting1'],
            'completionEvents' => ['completionEvent1'],
        ], $module->toArray());
    }

    /** @test */
    public function toJson_returns_a_json_encoded_array_of_parameters(){
        $module = new Module;
        $module->setAlias('alias1');
        $module->setName('name1');
        $module->setDescription('description1');
        $module->setPermissions(['permission1']);
        $module->setSettings(['setting1']);
        $module->setCompletionEvents(['completionEvent1']);

        $this->assertEquals(json_encode([
            'alias' => 'alias1',
            'name' => 'name1',
            'description' => 'description1',
            'permissions' => ['permission1'],
            'settings' => ['setting1'],
            'completionEvents' => ['completionEvent1'],
        ]), $module->toJson());
    }

    /** @test */
    public function __toString_returns_a_json_encoded_array_of_parameters(){
        $module = new Module;
        $module->setAlias('alias1');
        $module->setName('name1');
        $module->setDescription('description1');
        $module->setPermissions(['permission1']);
        $module->setSettings(['setting1']);
        $module->setCompletionEvents(['completionEvent1']);

        $this->assertEquals(json_encode([
            'alias' => 'alias1',
            'name' => 'name1',
            'description' => 'description1',
            'permissions' => ['permission1'],
            'settings' => ['setting1'],
            'completionEvents' => ['completionEvent1'],
        ]), (string)$module);
    }
}
