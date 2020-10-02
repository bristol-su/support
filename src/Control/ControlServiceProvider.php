<?php

namespace BristolSU\Support\Control;

use BristolSU\ControlDB\Contracts\Models\DataGroup;
use BristolSU\ControlDB\Contracts\Models\DataPosition;
use BristolSU\ControlDB\Contracts\Models\DataRole;
use BristolSU\ControlDB\Contracts\Models\DataUser;
use BristolSU\Support\Control\Settings\Attributes\AdditionalAttributesGroup;
use BristolSU\Support\Control\Settings\Attributes\AdditionalAttributesPosition;
use BristolSU\Support\Control\Settings\Attributes\AdditionalAttributesRole;
use BristolSU\Support\Control\Settings\Attributes\AdditionalAttributesUser;
use BristolSU\Support\Control\Settings\Attributes\AttributeGroup;
use BristolSU\Support\Control\Settings\ControlCategory;
use BristolSU\Support\Settings\Definition\DefinitionStore;
use BristolSU\Support\Settings\SettingRepository;
use Illuminate\Support\ServiceProvider;

class ControlServiceProvider extends ServiceProvider
{

    public function boot()
    {
        app(DefinitionStore::class)->register(AdditionalAttributesUser::class, ControlCategory::class, AttributeGroup::class);
        app(DefinitionStore::class)->register(AdditionalAttributesGroup::class, ControlCategory::class, AttributeGroup::class);
        app(DefinitionStore::class)->register(AdditionalAttributesRole::class, ControlCategory::class, AttributeGroup::class);
        app(DefinitionStore::class)->register(AdditionalAttributesPosition::class, ControlCategory::class, AttributeGroup::class);

        $settingRepository = $this->app->make(SettingRepository::class);
        try {
            foreach($settingRepository->get('Control.AdditionalAttribute.User') as $attribute) {
                (app(DataUser::class))::addProperty($attribute['key']);
            }

            foreach($settingRepository->get('Control.AdditionalAttribute.Group') as $attribute) {
                (app(DataGroup::class))::addProperty($attribute['key']);
            }

            foreach($settingRepository->get('Control.AdditionalAttribute.Role') as $attribute) {
                (app(DataRole::class))::addProperty($attribute['key']);
            }

            foreach($settingRepository->get('Control.AdditionalAttribute.Position') as $attribute) {
                (app(DataPosition::class))::addProperty($attribute['key']);
            }

        } catch (\Exception $e) {
            // TODO Handle this exception. This occurs when commands are run but the database isn't migrated so the attribute col doesn't exist
        }

    }

}
