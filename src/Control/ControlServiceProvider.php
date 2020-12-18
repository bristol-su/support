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
use BristolSU\Support\Settings\Concerns\RegistersSettings;
use Illuminate\Database\QueryException;
use Illuminate\Support\ServiceProvider;

class ControlServiceProvider extends ServiceProvider
{
    use RegistersSettings;

    public function boot()
    {
        $this->registerSettings()
            ->category(new ControlCategory())
            ->group(new AttributeGroup())
            ->registerSetting(new AdditionalAttributesUser())
            ->registerSetting(new AdditionalAttributesGroup())
            ->registerSetting(new AdditionalAttributesRole())
            ->registerSetting(new AdditionalAttributesPosition());

        try {
            foreach(AdditionalAttributesUser::getValue() as $attribute) {
                (app(DataUser::class))::addProperty($attribute['key']);
            }

            foreach(AdditionalAttributesGroup::getValue() as $attribute) {
                (app(DataGroup::class))::addProperty($attribute['key']);
            }

            foreach(AdditionalAttributesRole::getValue() as $attribute) {
                (app(DataRole::class))::addProperty($attribute['key']);
            }

            foreach(AdditionalAttributesPosition::getValue() as $attribute) {
                (app(DataPosition::class))::addProperty($attribute['key']);
            }
        } catch (QueryException $e) {
            // Additional attributes couldn't be loaded as settings table hasn't yet been migrated.
        }

    }

}
