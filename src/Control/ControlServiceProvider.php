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
use BristolSU\Support\Settings\SettingRepository;
use Illuminate\Support\ServiceProvider;

class ControlServiceProvider extends ServiceProvider
{
    use RegistersSettings;

    public function boot(SettingRepository $settingRepository)
    {
        $this->registerSettings()
            ->category(new ControlCategory())
            ->group(new AttributeGroup())
            ->registerSetting(new AdditionalAttributesUser())
            ->registerSetting(new AdditionalAttributesGroup())
            ->registerSetting(new AdditionalAttributesRole())
            ->registerSetting(new AdditionalAttributesPosition());

        try {
            foreach($settingRepository->getGlobalValue(AdditionalAttributesUser::getKey()) as $attribute) {
                (app(DataUser::class))::addProperty($attribute['key']);
            }

            foreach($settingRepository->get('control.data-fields.Group') as $attribute) {
                (app(DataGroup::class))::addProperty($attribute['key']);
            }

            foreach($settingRepository->get('control.data-fields.Role') as $attribute) {
                (app(DataRole::class))::addProperty($attribute['key']);
            }

            foreach($settingRepository->get('control.data-fields.Position') as $attribute) {
                (app(DataPosition::class))::addProperty($attribute['key']);
            }

        } catch (\Exception $e) {
//             TODO Handle this exception. This occurs when commands are run but the database isn't migrated so the attribute col doesn't exist
        }

    }

}
