<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateSettingValues extends Migration
{

    /**
     * Run the migrations.
     *
     */
    public function up()
    {
        foreach ($this->getKeysToChange() as $original => $new) {
            \BristolSU\Support\Settings\Saved\SavedSettingModel::key($original)->update(['key' => $new]);
        }
    }

    protected function getKeysToChange(): array
    {
        return [
            'additional_attributes.user' => \BristolSU\Support\Control\Settings\Attributes\AdditionalAttributesUser::getKey(),
            'additional_attributes.group' => \BristolSU\Support\Control\Settings\Attributes\AdditionalAttributesGroup::getKey(),
            'additional_attributes.role' => \BristolSU\Support\Control\Settings\Attributes\AdditionalAttributesRole::getKey(),
            'additional_attributes.position' => \BristolSU\Support\Control\Settings\Attributes\AdditionalAttributesPosition::getKey()
        ];
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down()
    {
        foreach ($this->getKeysToChange() as $original => $new) {
            \BristolSU\Support\Settings\Saved\SavedSettingModel::key($new)->update(['key' => $original]);
        }
    }
}
