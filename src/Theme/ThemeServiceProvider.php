<?php

namespace BristolSU\Support\Theme;

use BristolSU\Support\Settings\Concerns\RegistersSettings;
use BristolSU\Support\Theme\Settings\AppearanceCategory;
use BristolSU\Support\Theme\Settings\ChosenTheme;
use BristolSU\Support\Theme\Settings\ThemeGroup;
use Illuminate\Database\QueryException;
use Illuminate\Support\ServiceProvider;
use Twigger\Blade\Foundation\ThemeStore;

class ThemeServiceProvider extends ServiceProvider
{
    use RegistersSettings;

    public function register()
    {
        // No registration
    }

    public function boot()
    {
        $this->registerSettings()
            ->category(new AppearanceCategory())
            ->group(new ThemeGroup())
            ->registerSetting(new ChosenTheme(app(ThemeStore::class)));

        try {
            $theme = ChosenTheme::getValue();
            \Twigger\Blade\ThemeServiceProvider::useTheme($theme);
        } catch (QueryException $e) {
            // Theme couldn't be loaded as settings table hasn't yet been migrated.
        }

        $this->app['config']->set('themes.tag-prefix', 'portal');
    }

}
